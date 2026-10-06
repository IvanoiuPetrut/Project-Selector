<?php
// Included at the top of every page and action: configuration, session, database and helpers.

require __DIR__ . '/icons.php';

const ROLE_STUDENT = 1;
const ROLE_TEACHER = 2;
const ROLE_ADMIN = 3;

const MAX_TAKERS_PER_GROUP = 2; // students of one group that can take the same project

const NAME_PATTERN = '[A-Za-z]{3,32}';
const GROUP_PATTERN = '[0-9]{3}/[1-9]';
const PASSWORD_PATTERN = '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,64}';
const PASSWORD_HINT = 'At least 8 characters, with a number, an uppercase and a lowercase letter';

$debug = getenv('APP_DEBUG') === '1';
error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');

set_exception_handler(function (Throwable $e) use ($debug) {
  error_log((string) $e);
  http_response_code(500);
  echo $debug ? '<pre>' . e((string) $e) . '</pre>' : 'Something went wrong, please try again later.';
});

session_start([
  'cookie_httponly' => true,
  'cookie_samesite' => 'Lax',
  'use_strict_mode' => true,
]);

function db(): PDO
{
  static $pdo = null;
  if ($pdo === null) {
    $dsn = sprintf(
      'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
      getenv('DB_HOST') ?: 'localhost',
      (int) (getenv('DB_PORT') ?: 3306),
      getenv('DB_NAME') ?: 'project_selector'
    );
    $pdo = new PDO($dsn, getenv('DB_USER') ?: 'root', getenv('DB_PASSWORD') ?: '', [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]);
  }
  return $pdo;
}

// Runs a prepared statement and returns it, ready for fetch()/fetchAll().
function query(string $sql, array $params = []): PDOStatement
{
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  return $stmt;
}

function is_duplicate_key(PDOException $e): bool
{
  return ($e->errorInfo[1] ?? null) === 1062;
}

// Escapes a value for HTML output.
function e(mixed $value): string
{
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $to): never
{
  header('Location: ' . $to);
  exit;
}

// $type is 'success' or 'errors'.
function flash(string $type, string $message): void
{
  $_SESSION['flash'][$type][] = $message;
}

function take_flashes(): array
{
  $flashes = $_SESSION['flash'] ?? [];
  unset($_SESSION['flash']);
  return $flashes;
}

function current_user(): ?array
{
  return $_SESSION['user'] ?? null;
}

function user_role(): int
{
  return current_user()['role'] ?? 0;
}

function has_role(int ...$roles): bool
{
  return in_array(user_role(), $roles, true);
}

function require_role(int ...$roles): void
{
  if (!current_user()) {
    flash('errors', 'Please log in first');
    redirect('login.php');
  }
  if (!has_role(...$roles)) {
    flash('errors', 'You are not allowed to do that');
    redirect('index.php');
  }
}

function login_user(array $user): void
{
  session_regenerate_id(true);
  $_SESSION['user'] = [
    'id' => (int) $user['id'],
    'name' => $user['first_name'],
    'role' => (int) $user['id_role'],
    'group' => $user['id_group'] === null ? null : (int) $user['id_group'],
  ];
}

function csrf_token(): string
{
  return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function csrf_field(): string
{
  return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '" />';
}

// Rejects anything that is not a POST carrying this session's CSRF token.
function require_post(): void
{
  if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals(csrf_token(), (string) ($_POST['csrf'] ?? ''))) {
    http_response_code(400);
    exit('Invalid request, please go back and try again.');
  }
}

function input(string $key): string
{
  return trim((string) ($_POST[$key] ?? ''));
}

function input_int(string $key, ?array $source = null): int
{
  return (int) filter_var(($source ?? $_POST)[$key] ?? 0, FILTER_VALIDATE_INT, ['options' => ['default' => 0]]);
}

function matches(string $pattern, string $value): bool
{
  return preg_match('~^(?:' . $pattern . ')$~', $value) === 1;
}

function normalize_name(string $name): string
{
  return ucfirst(strtolower($name));
}

function find_group_id(string $name): ?int
{
  $id = query('SELECT id FROM `groups` WHERE name = ?', [$name])->fetchColumn();
  return $id === false ? null : (int) $id;
}

// Checks a password against the stored hash; accepts legacy unsalted sha256 hashes
// and upgrades them (and outdated bcrypt hashes) in place.
function verify_password(array $user, string $password): bool
{
  $stored = $user['password'];
  $valid = password_verify($password, $stored)
    || (strlen($stored) === 64 && hash_equals($stored, hash('sha256', $password)));

  if ($valid && (strlen($stored) === 64 || password_needs_rehash($stored, PASSWORD_DEFAULT))) {
    query('UPDATE users SET password = ? WHERE id = ?', [password_hash($password, PASSWORD_DEFAULT), $user['id']]);
  }
  return $valid;
}
