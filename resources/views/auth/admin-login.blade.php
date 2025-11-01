@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<section class="admin-login-section">
    <div class="admin-login-container">
        <div class="admin-login-card">
            <div class="admin-header">
                <div class="admin-logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1>Admin Portal</h1>
                <p>FullTimeZ Administration</p>
            </div>
            
            @if ($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('admin.login.post') }}" method="POST" class="admin-form">
                @csrf
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus 
                           placeholder="Enter admin email" />
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter password" />
                </div>
                
                <button type="submit" class="login-button">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>
            
            <div class="admin-footer">
                <a href="{{ route('home') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Back to Website
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.admin-login-section {
    min-height: 100vh;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.admin-login-container {
    width: 100%;
    max-width: 400px;
}

.admin-login-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
}

.admin-login-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #1e3c72, #2a5298, #2773e8);
}

.admin-header {
    text-align: center;
    padding: 40px 30px 30px;
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
}

.admin-logo {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 8px 16px rgba(30, 60, 114, 0.3);
}

.admin-logo i {
    font-size: 28px;
    color: #ffffff;
}

.admin-header h1 {
    color: #1a202c;
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 8px 0;
}

.admin-header p {
    color: #718096;
    font-size: 14px;
    margin: 0;
}

.admin-form {
    padding: 30px;
}

.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    font-size: 14px;
}

.input-group input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f7fafc;
    box-sizing: border-box;
}

.input-group input:focus {
    outline: none;
    border-color: #1e3c72;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
}

.login-button {
    width: 100%;
    padding: 14px 20px;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.login-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(30, 60, 114, 0.3);
}

.admin-footer {
    padding: 20px 30px 30px;
    text-align: center;
    background: #f8f9ff;
}

.back-link {
    color: #1e3c72;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: #2a5298;
}

.error-message {
    margin: 20px 30px;
    padding: 12px 16px;
    background: #fed7d7;
    color: #c53030;
    border-radius: 8px;
    border-left: 4px solid #e53e3e;
    font-size: 14px;
}

.error-message i {
    margin-right: 8px;
}

.error-message ul {
    margin: 8px 0 0 0;
    padding-left: 20px;
}

.error-message li {
    margin: 4px 0;
}

@media (max-width: 480px) {
    .admin-login-container {
        max-width: 100%;
    }
    
    .admin-form {
        padding: 20px;
    }
    
    .admin-header {
        padding: 30px 20px 20px;
    }
    
    .admin-footer {
        padding: 15px 20px 20px;
    }
}
</style>
@endsection
