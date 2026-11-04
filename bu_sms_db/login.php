<?php
session_start();

// Logout Handler
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Auto-redirect if logged in
if (isset($_SESSION['bu_admin_logged_in']) && $_SESSION['bu_admin_logged_in'] === true) {
    header("Location: admin_dashboard.html");
    exit;
}
if (isset($_SESSION['bu_user_logged_in']) && $_SESSION['bu_user_logged_in'] === true) {
    header("Location: user_dashboard.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bicol University - Student Management System</title>
    <style>
        /* Styles extracted from log.html */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .background { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
        .background img { width: 100%; height: 100%; object-fit: cover; }
        .background::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); }
        
        .container { min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; position: relative; }
        .welcome-screen { text-align: center; color: white; animation: fadeIn 1s ease-in; margin-bottom: 15px; }
        
        h1 { font-size: 3.5rem; font-weight: 700; color: #ffffff; margin-bottom: 15px; text-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); letter-spacing: 2px; }
        .subtitle { font-size: 1.3rem; color: rgba(255, 255, 255, 0.9); margin-top: 5px; margin-bottom: 50px; font-weight: 300; letter-spacing: 1px; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .logo { width: 180px; height: 180px; margin: 0 auto 30px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); overflow: hidden; padding: 10px; }
        .logo img { width: 200%; height: 200%; object-fit: contain; }

        .btn-group { display: flex; gap: 30px; justify-content: center; flex-wrap: wrap; }
        .btn { padding: 18px 48px; font-size: 1.2em; border: none; border-radius: 45px; cursor: pointer; transition: all 0.4s; font-weight: 600; letter-spacing: 1px; position: relative; overflow: hidden; text-transform: uppercase; }
        .btn-primary { background: transparent; color: white; border: 3px solid white; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); }
        .btn-primary:hover { transform: translateY(-5px) scale(1.05); background: white; color: #667eea; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4); }
        .btn-secondary { background: transparent; color: white; border: 3px solid white; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }
/* Find this block in your CSS and replace it */
.btn-secondary:hover { 
    transform: translateY(-5px) scale(1.05); 
    background: white; /* Changed from rgba(...) to solid white */
    color: #667eea;    /* Added: Changes text to blue/purple so it is readable */
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3); 
}
        .auth-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: none; justify-content: center; align-items: center; z-index: 1000; }
        .auth-modal.active { display: flex; animation: modalFadeIn 0.5s ease; }
        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-backdrop { position: absolute; top: 0; left: 0; width: 100%; height: 100%; backdrop-filter: blur(10px); background: rgba(0, 0, 0, 0.6); }
        
        .auth-container { background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(20px); border-radius: 30px; padding: 50px; width: 90%; max-width: 450px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.2); position: relative; animation: slideUp 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        @keyframes slideUp { from { opacity: 0; transform: translateY(100px) scale(0.5); } to { opacity: 1; transform: translateY(0) scale(1); } }

        .auth-container h2 { color: white; margin-bottom: 30px; font-size: 2.2em; text-align: center; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); }
        
        .close-btn { position: absolute; top: 20px; right: 20px; background: rgba(255, 255, 255, 0.2); border: none; color: white; font-size: 28px; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; line-height: 1; }
        .close-btn:hover { background: rgba(255, 255, 255, 0.3); transform: rotate(90deg); }

        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; color: white; margin-bottom: 8px; font-weight: 600; font-size: 1em; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2); }
        .form-group input { width: 100%; padding: 15px 20px; border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 15px; font-size: 1em; background: rgba(255, 255, 255, 0.9); transition: all 0.3s; color: #333; }
        .form-group input:focus { outline: none; border-color: white; background: white; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2); transform: translateY(-2px); }
        
        .submit-btn { width: 100%; padding: 16px; background: white; color: #667eea; border: none; border-radius: 15px; font-size: 1.2em; font-weight: 700; cursor: pointer; transition: all 0.3s; margin-top: 10px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); text-transform: uppercase; }
        .submit-btn:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3); background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%); }
        
        .form-footer { text-align: center; margin-top: 25px; color: white; font-size: 0.95em; }
        .form-footer a { color: white; text-decoration: none; font-weight: 600; border-bottom: 2px solid white; transition: all 0.3s; cursor: pointer; }
        .form-footer a:hover { opacity: 0.8; border-bottom-color: transparent; }

        .error-message { background: rgba(255, 0, 0, 0.2); border: 1px solid rgba(255, 0, 0, 0.4); color: white; padding: 12px 15px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9em; display: none; animation: shake 0.5s; }
        .success-message { background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); color: white; padding: 12px 15px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9em; display: none; }
        .show { display: block; }
        .info-text { color: white; font-size: 0.85em; margin-top: 5px; opacity: 0.9; }
        
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }
        @media (max-width: 768px) { .welcome-screen h1 { font-size: 2.2em; } .btn-group { flex-direction: column; gap: 20px; } }
    </style>
</head>
<body>
    <div class="background"><img src="IMG.jpg" alt="Background"></div>

    <div class="container">
        <div class="welcome-screen">
            <div class="logo"><img src="BU_logo.png" alt="BU Logo"></div>
            <h1>Welcome to Bicol University</h1>
            <p class="subtitle">Student Management System</p>
            <div class="btn-group">
                <button class="btn btn-primary" onclick="openModal('admin-login')"><span>🔐 Admin Login</span></button>
                <button class="btn btn-secondary" onclick="openModal('student-login')"><span>👤 Student Login</span></button>
            </div>
        </div>
    </div>

    <div class="auth-modal" id="admin-loginModal">
        <div class="modal-backdrop" onclick="closeModal('admin-login')"></div>
        <div class="auth-container">
            <button class="close-btn" onclick="closeModal('admin-login')">×</button>
            <h2>Admin Login</h2>
            <form onsubmit="handleAuth(event, 'admin-login')">
                <div class="error-message" id="admin-login-error"></div>
                <div class="success-message" id="admin-login-success"></div>
                <div class="form-group">
                    <label>School Email</label>
                    <input type="email" name="email" placeholder="admin@bicol-u.edu.ph" required autofocus>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="submit-btn">Login as Admin</button>
                <div class="form-footer">Don't have an account? <a onclick="switchModal('admin-login', 'admin-signup')">Sign Up</a></div>
            </form>
        </div>
    </div>

    <div class="auth-modal" id="admin-signupModal">
        <div class="modal-backdrop" onclick="closeModal('admin-signup')"></div>
        <div class="auth-container">
            <button class="close-btn" onclick="closeModal('admin-signup')">×</button>
            <h2>Admin Sign Up</h2>
            <form onsubmit="handleAuth(event, 'admin-signup')">
                <div class="error-message" id="admin-signup-error"></div>
                <div class="success-message" id="admin-signup-success"></div>
                <div class="form-group"><label>Full Name</label><input type="text" name="full_name" required></div>
                <div class="form-group">
                    <label>School Email</label>
                    <input type="email" name="email" placeholder="admin@bicol-u.edu.ph" required>
                    <p class="info-text">Must be a @bicol-u.edu.ph address</p>
                </div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <div class="form-group"><label>Admin Code</label><input type="text" name="auth_code" placeholder="Enter admin code" required></div>
                <button type="submit" class="submit-btn">Create Admin Account</button>
                <div class="form-footer">Already have an account? <a onclick="switchModal('admin-signup', 'admin-login')">Login</a></div>
            </form>
        </div>
    </div>

    <div class="auth-modal" id="student-loginModal">
        <div class="modal-backdrop" onclick="closeModal('student-login')"></div>
        <div class="auth-container">
            <button class="close-btn" onclick="closeModal('student-login')">×</button>
            <h2>Student Login</h2>
            <form onsubmit="handleAuth(event, 'student-login')">
                <div class="error-message" id="student-login-error"></div>
                <div class="success-message" id="student-login-success"></div>
                <div class="form-group"><label>School Email</label><input type="email" name="email" placeholder="student@bicol-u.edu.ph" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <button type="submit" class="submit-btn">Login as Student</button>
                <div class="form-footer">Don't have an account? <a onclick="switchModal('student-login', 'student-signup')">Sign Up</a></div>
            </form>
        </div>
    </div>

    <div class="auth-modal" id="student-signupModal">
        <div class="modal-backdrop" onclick="closeModal('student-signup')"></div>
        <div class="auth-container">
            <button class="close-btn" onclick="closeModal('student-signup')">×</button>
            <h2>Student Sign Up</h2>
            <form onsubmit="handleAuth(event, 'student-signup')">
                <div class="error-message" id="student-signup-error"></div>
                <div class="success-message" id="student-signup-success"></div>
                <div class="form-group"><label>Full Name</label><input type="text" name="full_name" required></div>
                <div class="form-group"><label>Student ID</label><input type="text" name="student_id" required></div>
                <div class="form-group">
                    <label>School Email</label>
                    <input type="email" name="email" placeholder="student@bicol-u.edu.ph" required>
                    <p class="info-text">Must be a @bicol-u.edu.ph address</p>
                </div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <button type="submit" class="submit-btn">Create Account</button>
                <div class="form-footer">Already have an account? <a onclick="switchModal('student-signup', 'student-login')">Login</a></div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id+'Modal').classList.add('active'); }
        function closeModal(id) { 
            const modal = document.getElementById(id+'Modal');
            modal.classList.remove('active');
            // Clear inputs and messages
            modal.querySelector('form').reset();
            const err = document.getElementById(id+'-error');
            const suc = document.getElementById(id+'-success');
            if(err) err.classList.remove('show');
            if(suc) suc.classList.remove('show');
        }
        function switchModal(close, open) { closeModal(close); setTimeout(() => openModal(open), 300); }

        async function handleAuth(e, type) {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            const originalText = btn.innerText;
            btn.innerText = 'Processing...';
            btn.disabled = true;

            const formData = new FormData(e.target);
            const errorDiv = document.getElementById(type + '-error');
            const successDiv = document.getElementById(type + '-success');
            errorDiv.classList.remove('show');
            successDiv.classList.remove('show');

            // --- STRICT DOMAIN CHECK (Frontend) ---
            const email = formData.get('email').toLowerCase();
            if (!email.endsWith('@bicol-u.edu.ph')) {
                errorDiv.innerText = '❌ Access Denied: You must use a @bicol-u.edu.ph email address.';
                errorDiv.classList.add('show');
                btn.innerText = originalText;
                btn.disabled = false;
                return;
            }

            // Determine endpoint and action
            let endpoint = '';
            
            if (type === 'admin-login') {
                endpoint = 'auth.php';
                formData.append('action', 'login_admin');
            } else if (type === 'student-login') {
                endpoint = 'auth.php';
                formData.append('action', 'login_student');
            } else if (type === 'admin-signup') {
                endpoint = 'signup.php';
                formData.append('action', 'signup_admin');
            } else if (type === 'student-signup') {
                endpoint = 'signup.php';
                formData.append('action', 'signup_student');
            }

            try {
                const response = await fetch(endpoint, { method: 'POST', body: formData });
                const result = await response.json();

                if (result.success) {
                    successDiv.innerText = 'Success! Redirecting...';
                    successDiv.classList.add('show');
                    
                    setTimeout(() => {
                        if (type === 'admin-login' || type === 'admin-signup') {
                            if(type.includes('login')) sessionStorage.setItem('bu_admin_logged_in', 'true');
                            window.location.href = (type === 'admin-signup') ? 'login.php' : 'admin_dashboard.html';
                        } else {
                            if(type.includes('login')) sessionStorage.setItem('bu_user_logged_in', 'true');
                            window.location.href = (type === 'student-signup') ? 'login.php' : 'user_dashboard.html';
                        }
                    }, 1500);
                } else {
                    errorDiv.innerText = result.message || 'Error occurred';
                    errorDiv.classList.add('show');
                }
            } catch (err) {
                errorDiv.innerText = 'Connection Error';
                errorDiv.classList.add('show');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>