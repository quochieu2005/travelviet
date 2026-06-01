import React, { useState } from 'react';

function Register() {
  const [formData, setFormData] = useState({
    fullName: '',
    email: '',
    phone: '',
    password: '',
    confirmPassword: ''
  });
  const [agreeTerms, setAgreeTerms] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (formData.password !== formData.confirmPassword) {
      alert("Passwords don't match!");
      return;
    }
    console.log('Register attempt:', { ...formData, agreeTerms });
  };

  const handleGoogleRegister = () => {
    console.log('Register with Google');
  };

  const handleFacebookRegister = () => {
    console.log('Register with Facebook');
  };

  return (
    <div className="register-page">
      <div className="register-grid">
        {/* Left Side - Form */}
        <div className="register-form-side">
          <div className="form-card">
            <div className="form-header">
              <h2>Create account</h2>
              <p>Please fill in your information to continue</p>
            </div>

            <form onSubmit={handleSubmit} className="register-form">
              <div className="form-field">
                <label>Full name</label>
                <input
                  type="text"
                  name="fullName"
                  placeholder="John Doe"
                  value={formData.fullName}
                  onChange={handleChange}
                  required
                />
              </div>

              <div className="form-field">
                <label>Email address</label>
                <input
                  type="email"
                  name="email"
                  placeholder="hello@example.com"
                  value={formData.email}
                  onChange={handleChange}
                  required
                />
              </div>

              <div className="form-field">
                <label>Phone number</label>
                <input
                  type="tel"
                  name="phone"
                  placeholder="+1 234 567 8900"
                  value={formData.phone}
                  onChange={handleChange}
                />
              </div>

              <div className="form-field">
                <label>Password</label>
                <div className="password-input-wrapper">
                  <input
                    type={showPassword ? "text" : "password"}
                    name="password"
                    placeholder="Create a password"
                    value={formData.password}
                    onChange={handleChange}
                    required
                  />
                  <button 
                    type="button"
                    className="password-eye"
                    onClick={() => setShowPassword(!showPassword)}
                  >
                    {showPassword ? "HIDE" : "SHOW"}
                  </button>
                </div>
              </div>

              <div className="form-field">
                <label>Confirm password</label>
                <div className="password-input-wrapper">
                  <input
                    type={showConfirmPassword ? "text" : "password"}
                    name="confirmPassword"
                    placeholder="Confirm your password"
                    value={formData.confirmPassword}
                    onChange={handleChange}
                    required
                  />
                  <button 
                    type="button"
                    className="password-eye"
                    onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                  >
                    {showConfirmPassword ? "HIDE" : "SHOW"}
                  </button>
                </div>
              </div>

              <div className="terms-checkbox">
                <label>
                  <input
                    type="checkbox"
                    checked={agreeTerms}
                    onChange={(e) => setAgreeTerms(e.target.checked)}
                    required
                  />
                  <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
                </label>
              </div>

              <button type="submit" className="submit-button">
                Create account
              </button>
            </form>

            <div className="divider">
              <span>or continue with</span>
            </div>

            <div className="social-login">
              <button className="social-button google" onClick={handleGoogleRegister}>
                <svg width="20" height="20" viewBox="0 0 24 24">
                  <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                  <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                  <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                  <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
              </button>
              <button className="social-button facebook" onClick={handleFacebookRegister}>
                <svg width="20" height="20" viewBox="0 0 24 24">
                  <path fill="#1877F2" d="M24 12.07C24 5.41 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.8-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.96h-1.51c-1.49 0-1.95.93-1.95 1.89v2.26h3.32l-.53 3.49h-2.79V24C19.62 23.1 24 18.1 24 12.07z"/>
                </svg>
                Continue with Facebook
              </button>
            </div>

            <p className="login-link">
              Already have an account? <a href="/Login">Sign in</a>
            </p>
          </div>
        </div>

        {/* Right Side - Hero Section */}
        <div className="register-hero-side">
          <div className="hero-content">
            <div className="hero-badge">Join us today</div>
            <h1>Start your journey</h1>
            <p>Get access to exclusive deals, personalized recommendations, and seamless booking experience.</p>
            
            <div className="feature-list">
              <div className="feature">
                <div className="feature-marker"></div>
                <div>
                  <h4>Exclusive deals</h4>
                  <p>Get member-only prices</p>
                </div>
              </div>
              <div className="feature">
                <div className="feature-marker"></div>
                <div>
                  <h4>Best price guarantee</h4>
                  <p>We match any price</p>
                </div>
              </div>
              <div className="feature">
                <div className="feature-marker"></div>
                <div>
                  <h4>Rewards program</h4>
                  <p>Earn points on every booking</p>
                </div>
              </div>
              <div className="feature">
                <div className="feature-marker"></div>
                <div>
                  <h4>Secure booking</h4>
                  <p>Your data is safe with us</p>
                </div>
              </div>
            </div>

            <div className="hero-stats">
              <div className="stat">
                <strong>50k+</strong>
                <span>Happy travelers</span>
              </div>
              <div className="stat">
                <strong>120+</strong>
                <span>Destinations</span>
              </div>
              <div className="stat">
                <strong>24/7</strong>
                <span>Support</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Register;