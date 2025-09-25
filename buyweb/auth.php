<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost","DB_USERNAME","DB_PASSWORD","jenil_ff");

if($conn->connect_error){
    echo json_encode(['status'=>'error','message'=>'Database connection failed']);
    exit;
}

$action = $_POST['action'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$name = $_POST['name'] ?? '';
$username = $_POST['username'] ?? '';

if($action == 'register'){
    if(empty($name) || empty($username) || empty($email) || empty($password)){
        echo json_encode(['status'=>'error','message'=>'Please fill all fields']);
        exit;
    }
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss",$name,$username,$email,$passwordHash);
    if($stmt->execute()){
        echo json_encode(['status'=>'success','message'=>'Registration successful']);
    } else {
        echo json_encode(['status'=>'error','message'=>'User already exists or invalid data']);
    }
    $stmt->close();
}
else if($action == 'login'){
    if(empty($email) || empty($password)){
        echo json_encode(['status'=>'error','message'=>'Please fill all fields']);
        exit;
    }
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        if(password_verify($password,$user['password'])){
            echo json_encode(['status'=>'success','message'=>'Login successful']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Incorrect password']);
        }
    } else {
        echo json_encode(['status'=>'error','message'=>'User not found']);
    }
    $stmt->close();
}
else{
    echo json_encode(['status'=>'error','message'=>'Invalid action']);
}

$conn->close();
?>