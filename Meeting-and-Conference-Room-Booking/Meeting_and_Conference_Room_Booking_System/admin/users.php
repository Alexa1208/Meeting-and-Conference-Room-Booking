<?php
session_start();
include '../config.php';

// ✅ Secure session role check
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = '';

// ✅ Handle Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    $check = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $check->execute([$username]);

    if ($check->rowCount() > 0) {
        $message = "<div class='alert alert-danger text-center'>⚠ Username already exists!</div>";
    } else {
$hashedPassword = md5($password);
$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $hashedPassword, $role]);

    }
}

// ✅ Handle Delete User
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    header("Location: users.php?deleted=1");
    exit;
}

$users = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users - Admin Panel</title>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ===== DARK NEON THEME ===== */
body {
    background-color: #0a0f1f;
    background-image: radial-gradient(circle at top right, #001133, #000);
    color: #e0e6f0;
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    margin: 0;
    padding: 40px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.card {
    background: rgba(15, 20, 35, 0.9);
    border: 1px solid rgba(0, 255, 255, 0.3);
    box-shadow: 0 0 25px rgba(0, 191, 255, 0.2);
    border-radius: 15px;
    padding: 40px;
}

h3 {
    color: #00bfff;
    text-shadow: 0 0 15px rgba(0, 191, 255, 0.8);
    font-weight: 600;
    text-align: center;
    margin-bottom: 30px;
}

/* Input + Select Styling */
label {
    font-weight: 500;
        margin-left:50px;
}
.form-control, .form-select {
    background-color: rgba(25, 30, 50, 0.8);
    border: 1px solid rgba(0, 191, 255, 0.3);
    color: #fff;
    border-radius: 8px;
    width:200px;
    margin-top:15px;
    height: 30px;
    margin-bottom:15px;
    margin-left:10px;
}
.form-control:focus, .form-select:focus {
    box-shadow: 0 0 10px #00bfff;
    border-color: #00ffff;
}

/* Button Styling */
.btn-success {
    background: linear-gradient(90deg, #00ffcc, #0099ff);
    border: none;
    color: #000;
    font-weight: 600;
    text-transform: uppercase;
    padding: 14px 40px;
    font-size: 16px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 191, 255, 0.3);
    transition: all 0.3s ease;
}
.btn-success:hover {
    box-shadow: 0 0 25px #00ffff, 0 0 40px #0099ff;
    transform: scale(1.05);
}

.btn-danger {
            background-color: rgba(0, 191, 255, 0.1);
            border: 1px solid #00ccffff;
            color: #fffbfbff;

        }
.btn-danger:hover {
    box-shadow: 0 0 15px #ff1a1a;
    transform: scale(1.05);

}

.btn-secondary {
    background: linear-gradient(90deg, #0077ff, #00ffff);
    border: none;
    color: #131212ff;
   font-weight: bold; 
    text-transform: uppercase;
    padding: 10px 30px;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
}
.btn-secondary:hover {
    box-shadow: 0 0 25px #00ffff, 0 0 50px #0077ff;
    transform: scale(1.05);
}
        .btn-sm {
            border-radius: 8px;
            padding: 8px 14px;
            font-weight: 500;
        }
/* Table Styling */
.table {
    width: 100%;
    color: #e0e6f0;
    border-color: rgba(0, 255, 255, 0.3);
    background-color: rgba(15, 20, 35, 0.8);
    border-radius: 12px;
    overflow: hidden;
    text-align: center;
    margin-top: 20px;
    margin-bottom: 20px;
    padding-top: 10px;
}
        .table tbody td {
            padding: 14px 14px;
            vertical-align: middle;
            border: none;
        }
.table thead {
    background-color: rgba(0, 191, 255, 0.15);
    text-transform: uppercase;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 191, 255, 0.1);
}
.btn-success.btn-sm {
    font-size: 14px;
    padding: 8px 25px !important;
    border-radius: 8px;
}

.alert {
    border-radius: 10px;
    text-align: center;
    margin-bottom: 20px;
}
</style>
</head>

<body>
<div class="container mt-4 card">
    <h3>👤 Manage Users</h3>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-warning">🗑 User deleted successfully.</div>
    <?php endif; ?>
    <?= $message ?>

<form method="POST" class="mb-4">
    <div class="row g-3 align-items-end justify-content-center">

        <!-- Username and Password on the same row -->
        <div class="col-md-12 d-flex justify-content-center gap-3">
            
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>

                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>

                <label>Role</label>
                <select name="role" class="form-select" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                           <button type="submit" class="btn btn-success btn-sm px-4 py-2">➕ Add User</button>

            
        
        </div>

        <!-- Role and Add User Button below -->
        

    </div>
</form>



    <!-- === USER LIST TABLE === -->
    <table class="table table-bordered table-hover align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
<td>
    <?php if ($_SESSION['user']['id'] != $u['id']): ?>
        <a href="?delete=<?= urlencode($u['id']) ?>"
           onclick="return confirm('Are you sure you want to delete this user?')"
           class="btn btn-danger btn-sm"
           title="Delete User">
            <i class="fas fa-trash-alt"></i>
        </a>
    <?php else: ?>
        <span class="text-muted">Current User</span>
    <?php endif; ?>
</td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-center mt-4" >
        <a href="index.php" class="btn btn-secondary">⬅ Back</a>
    </div>
</div>
</body>
</html>
