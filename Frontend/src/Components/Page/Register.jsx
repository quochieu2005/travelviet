import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import { register, googleAuth, facebookAuth } from '../../services/authService';
import { useGoogleLogin } from '@react-oauth/google';
import FacebookLoginButton from '../../context/FacebookLoginButton';

function Register() {
  const navigate = useNavigate();

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
  const [loading, setLoading] = useState(false);
  const [googleLoading, setGoogleLoading] = useState(false);
  const [facebookLoading, setFacebookLoading] = useState(false);
  const [error, setError] = useState('');

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
    if (error) setError('');
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    if (formData.password !== formData.confirmPassword) {
      setError('Mật khẩu xác nhận không khớp!');
      toast.error('Mật khẩu xác nhận không khớp!');
      return;
    }

    if (formData.password.length < 6) {
      setError('Mật khẩu phải có ít nhất 6 ký tự!');
      toast.error('Mật khẩu phải có ít nhất 6 ký tự!');
      return;
    }

    if (!agreeTerms) {
      setError('Vui lòng đồng ý với Điều khoản dịch vụ!');
      toast.error('Vui lòng đồng ý với Điều khoản dịch vụ!');
      return;
    }

    setLoading(true);
    try {
      const response = await register({
        full_name: formData.fullName,
        email: formData.email,
        phone: formData.phone,
        password: formData.password,
      });

      if (response.data.access_token) {
        localStorage.setItem('token', response.data.access_token);
      }
      localStorage.setItem('user', JSON.stringify(response.data.user));

      window.dispatchEvent(new Event('userLogin'));

      toast.success('Đăng ký thành công!');
      
      setTimeout(() => {
        navigate('/');
      }, 1000);

    } catch (err) {
      const msg = err.response?.data?.message ||
        err.response?.data?.errors ||
        'Đăng ký thất bại, vui lòng thử lại.';

      const errorMessage = typeof msg === 'object'
        ? Object.values(msg).flat().join(' ')
        : msg;

      setError(errorMessage);
      toast.error(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  // Google Register Handler
  const handleGoogleRegister = useGoogleLogin({
    onSuccess: async (tokenResponse) => {
      setGoogleLoading(true);
      try {
        const response = await googleAuth(tokenResponse.access_token);
        if (response.data.success) {
          localStorage.setItem('token', response.data.access_token);
          localStorage.setItem('user', JSON.stringify(response.data.user));
          window.dispatchEvent(new Event('userLogin'));
          toast.success(response.data.message || 'Đăng ký Google thành công!');
          setTimeout(() => navigate('/'), 1000);
        } else {
          toast.error(response.data.message || 'Đăng ký Google thất bại');
        }
      } catch (error) {
        let errorMsg = 'Đăng ký Google thất bại. Vui lòng thử lại.';
        if (error.response?.status === 403) {
          errorMsg = error.response?.data?.message || 'Email không được phép đăng ký';
        } else if (error.response?.status === 401) {
          errorMsg = 'Token Google không hợp lệ. Vui lòng thử lại.';
        } else if (error.response?.data?.message) {
          errorMsg = error.response.data.message;
        }
        toast.error(errorMsg);
        setError(errorMsg);
      } finally {
        setGoogleLoading(false);
      }
    },
    onError: () => {
      toast.error('Đăng nhập Google thất bại. Vui lòng thử lại.');
      setGoogleLoading(false);
    },
    flow: 'implicit',
  });

  // Facebook Register Handler
  const handleFacebookSuccess = async (authResponse) => {
    setFacebookLoading(true);
    try {
      const result = await facebookAuth(authResponse.accessToken);
      if (result.data.success) {
        localStorage.setItem('token', result.data.access_token);
        localStorage.setItem('user', JSON.stringify(result.data.user));
        window.dispatchEvent(new Event('userLogin'));
        toast.success(result.data.message || 'Đăng ký Facebook thành công!');
        setTimeout(() => navigate('/'), 1000);
      } else {
        toast.error(result.data.message || 'Đăng ký Facebook thất bại');
      }
    } catch (error) {
      toast.error(error.response?.data?.message || 'Đăng ký Facebook thất bại. Vui lòng thử lại.');
    } finally {
      setFacebookLoading(false);
    }
  };

  const handleFacebookFailure = (error) => {
    toast.error('Đăng ký Facebook thất bại. Vui lòng thử lại.');
    setFacebookLoading(false);
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

            {error && (
              <div className="error-message" style={{
                backgroundColor: '#fee',
                color: '#c33',
                padding: '10px',
                borderRadius: '8px',
                marginBottom: '20px',
                fontSize: '14px'
              }}>
                {error}
              </div>
            )}

            <form onSubmit={handleSubmit} className="register-form">

              <div className="form-field">
                <label>Full name *</label>
                <input
                  type="text"
                  name="fullName"
                  placeholder="John Doe"
                  value={formData.fullName}
                  onChange={handleChange}
                  required
                  disabled={loading || googleLoading || facebookLoading}
                />
              </div>

              <div className="form-field">
                <label>Email address *</label>
                <input
                  type="email"
                  name="email"
                  placeholder="hello@example.com"
                  value={formData.email}
                  onChange={handleChange}
                  required
                  disabled={loading || googleLoading || facebookLoading}
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
                  disabled={loading || googleLoading || facebookLoading}
                />
              </div>

              <div className="form-field">
                <label>Password *</label>
                <div className="password-input-wrapper">
                  <input
                    type={showPassword ? 'text' : 'password'}
                    name="password"
                    placeholder="Create a password (min 6 characters)"
                    value={formData.password}
                    onChange={handleChange}
                    required
                    disabled={loading || googleLoading || facebookLoading}
                  />
                  <button
                    type="button"
                    className="password-eye"
                    onClick={() => setShowPassword(!showPassword)}
                    disabled={loading || googleLoading || facebookLoading}
                  >
                    {showPassword ? 'HIDE' : 'SHOW'}
                  </button>
                </div>
              </div>

              <div className="form-field">
                <label>Confirm password *</label>
                <div className="password-input-wrapper">
                  <input
                    type={showConfirmPassword ? 'text' : 'password'}
                    name="confirmPassword"
                    placeholder="Confirm your password"
                    value={formData.confirmPassword}
                    onChange={handleChange}
                    required
                    disabled={loading || googleLoading || facebookLoading}
                  />
                  <button
                    type="button"
                    className="password-eye"
                    onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                    disabled={loading || googleLoading || facebookLoading}
                  >
                    {showConfirmPassword ? 'HIDE' : 'SHOW'}
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
                    disabled={loading || googleLoading || facebookLoading}
                  />
                  <span>
                    I agree to the <a href="/terms">Terms of Service</a> and{' '}
                    <a href="/privacy">Privacy Policy</a>
                  </span>
                </label>
              </div>

              <button
                type="submit"
                className="submit-button"
                disabled={loading || googleLoading || facebookLoading}
                style={{
                  opacity: (loading || googleLoading || facebookLoading) ? 0.7 : 1,
                  cursor: (loading || googleLoading || facebookLoading) ? 'not-allowed' : 'pointer'
                }}
              >
                {loading ? 'Đang tạo tài khoản...' :
                  googleLoading ? 'Đang xử lý Google...' :
                  facebookLoading ? 'Đang xử lý Facebook...' :
                  'Create account'}
              </button>

            </form>

            <div className="divider">
              <span>or continue with</span>
            </div>

            <div className="social-login">
              <button
                className="social-button google"
                onClick={handleGoogleRegister}
                disabled={loading || googleLoading || facebookLoading}
                style={{
                  opacity: (loading || googleLoading || facebookLoading) ? 0.7 : 1,
                  cursor: (loading || googleLoading || facebookLoading) ? 'not-allowed' : 'pointer'
                }}
              >
                <svg width="20" height="20" viewBox="0 0 24 24">
                  <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                  <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                  <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                  <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
                {googleLoading ? 'Processing...' : 'Continue with Google'}
              </button>

              <FacebookLoginButton
                appId="YOUR_FACEBOOK_APP_ID"
                onSuccess={handleFacebookSuccess}
                onError={handleFacebookFailure}
                disabled={facebookLoading || loading || googleLoading}
              >
                <button
                  className="social-button facebook"
                  disabled={facebookLoading || loading || googleLoading}
                  style={{ width: '100%' }}
                >
                  <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#1877F2" d="M24 12.07C24 5.41 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.8-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.96h-1.51c-1.49 0-1.95.93-1.95 1.89v2.26h3.32l-.53 3.49h-2.79V24C19.62 23.1 24 18.1 24 12.07z" />
                  </svg>
                  {facebookLoading ? 'Đang xử lý...' : 'Continue with Facebook'}
                </button>
              </FacebookLoginButton>
            </div>

            <p className="login-link">
              Already have an account?{' '}
              <a href="/Login" onClick={(e) => {
                e.preventDefault();
                navigate('/Login');
              }}>
                Sign in
              </a>
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