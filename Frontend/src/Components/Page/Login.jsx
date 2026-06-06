import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import { login, googleAuth, facebookAuth } from '../../services/authService';
import { useGoogleLogin } from '@react-oauth/google';
import FacebookLoginButton from '../../context/FacebookLoginButton';

function Login() {
  const navigate = useNavigate();

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [rememberMe, setRememberMe] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [googleLoading, setGoogleLoading] = useState(false);
  const [facebookLoading, setFacebookLoading] = useState(false);

  useEffect(() => {
    const rememberedEmail = localStorage.getItem('remembered_email');
    if (rememberedEmail) {
      setEmail(rememberedEmail);
      setRememberMe(true);
    }
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    try {
      const response = await login({ email, password });

      localStorage.setItem('token', response.data.access_token);
      localStorage.setItem('user', JSON.stringify(response.data.user));

      if (rememberMe) {
        localStorage.setItem('remembered_email', email);
      } else {
        localStorage.removeItem('remembered_email');
      }

      window.dispatchEvent(new Event('userLogin'));
      sessionStorage.setItem('loginSuccess', '1');
      
      toast.success(response.data.message || 'Đăng nhập thành công!');
      navigate('/');

    } catch (err) {
      const msg = err.response?.data?.message || 'Đăng nhập thất bại, vui lòng thử lại.';
      toast.error(msg);
    } finally {
      setLoading(false);
    }
  };

  // Google Login Handler
  const googleLogin = useGoogleLogin({
    onSuccess: async (tokenResponse) => {
      setGoogleLoading(true);
      
      try {
        const response = await googleAuth(tokenResponse.access_token);
        
        if (response.data.success) {
          localStorage.setItem('token', response.data.access_token);
          localStorage.setItem('user', JSON.stringify(response.data.user));
          
          window.dispatchEvent(new Event('userLogin'));
          sessionStorage.setItem('loginSuccess', '1');
          
          toast.success(response.data.message);
          navigate('/');
        } else {
          toast.error(response.data.message || 'Đăng nhập Google thất bại');
        }
      } catch (error) {
        let errorMsg = 'Đăng nhập Google thất bại. Vui lòng thử lại.';
        
        if (error.response?.status === 404) {
          errorMsg = 'API endpoint không tồn tại. Vui lòng kiểm tra route.';
        } else if (error.response?.status === 500) {
          errorMsg = 'Lỗi server. Vui lòng kiểm tra log Laravel.';
        } else if (error.response?.data?.message) {
          errorMsg = error.response.data.message;
        }
        
        toast.error(errorMsg);
      } finally {
        setGoogleLoading(false);
      }
    },
    onError: () => {
      toast.error('Đăng nhập Google thất bại. Vui lòng thử lại.');
      setGoogleLoading(false);
    },
    flow: 'implicit',
    overrideDefaultRedirectUri: true,
    redirectUri: 'http://localhost:3000',
  });

  // Facebook Login Handler
  const handleFacebookSuccess = async (authResponse) => {
    console.log('Facebook login success:', authResponse);
    setFacebookLoading(true);
    
    try {
      // Gọi API backend với access_token từ Facebook
      const result = await facebookAuth(authResponse.accessToken);
      
      if (result.data.success) {
        localStorage.setItem('token', result.data.access_token);
        localStorage.setItem('user', JSON.stringify(result.data.user));
        
        window.dispatchEvent(new Event('userLogin'));
        sessionStorage.setItem('loginSuccess', '1');
        
        toast.success(result.data.message);
        navigate('/');
      } else {
        toast.error(result.data.message || 'Đăng nhập Facebook thất bại');
      }
    } catch (error) {
      console.error('Facebook login error:', error);
      let errorMsg = 'Đăng nhập Facebook thất bại. Vui lòng thử lại.';
      
      if (error.response?.status === 401) {
        errorMsg = 'Token Facebook không hợp lệ. Vui lòng thử lại.';
      } else if (error.response?.data?.message) {
        errorMsg = error.response.data.message;
      }
      
      toast.error(errorMsg);
    } finally {
      setFacebookLoading(false);
    }
  };

  const handleFacebookFailure = (error) => {
    console.error('Facebook login failed:', error);
    toast.error('Đăng nhập Facebook thất bại. Vui lòng thử lại.');
    setFacebookLoading(false);
  };

  const handleGoogleLogin = () => {
    googleLogin();
  };

  return (
    <div className="login-wrapper">
      <div className="login-container">
        <div className="login-form-container">
          <div className="login-header">
            <h2>Welcome Back</h2>
            <p>Sign in to continue your travel journey</p>
          </div>

          <form onSubmit={handleSubmit} className="login-form">
            <div className="input-group-custom">
              <label htmlFor="email">Email Address</label>
              <input
                type="email"
                id="email"
                placeholder="Enter your email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                disabled={loading || googleLoading || facebookLoading}
              />
            </div>

            <div className="input-group-custom">
              <label htmlFor="password">Password</label>
              <div className="password-input-wrapper">
                <input
                  type={showPassword ? 'text' : 'password'}
                  id="password"
                  placeholder="Enter your password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
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

            <div className="form-options">
              <label className="checkbox-label">
                <input
                  type="checkbox"
                  checked={rememberMe}
                  onChange={(e) => setRememberMe(e.target.checked)}
                  disabled={loading || googleLoading || facebookLoading}
                />
                <span>Remember me</span>
              </label>
              <a href="/forgot-password" className="forgot-link">Forgot Password?</a>
            </div>

            <button
              type="submit"
              className="login-btn"
              disabled={loading || googleLoading || facebookLoading}
            >
              {loading ? 'Đang đăng nhập...' : 'Sign In'}
            </button>
          </form>

          <div className="divider-section">
            <span>Or continue with</span>
          </div>

          <div className="social-buttons">
            <button 
              type="button" 
              className="social-btn google-btn" 
              onClick={handleGoogleLogin}
              disabled={googleLoading || loading || facebookLoading}
            >
              {googleLoading ? (
                <span>Đang xử lý...</span>
              ) : (
                <>
                  <svg className="social-icon" viewBox="0 0 24 24" width="20" height="20">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                  </svg>
                  Continue with Google
                </>
              )}
            </button>

            <FacebookLoginButton
              appId="YOUR_FACEBOOK_APP_ID"
              onSuccess={handleFacebookSuccess}
              onError={handleFacebookFailure}
              disabled={facebookLoading || loading || googleLoading}
            >
              <button
                type="button"
                className="social-btn facebook-btn"
                disabled={facebookLoading || loading || googleLoading}
                style={{ width: '100%' }}
              >
                {facebookLoading ? (
                  <span>Đang xử lý...</span>
                ) : (
                  <>
                    <svg className="social-icon" viewBox="0 0 24 24" width="20" height="20">
                      <path fill="#1877F2" d="M24 12.07C24 5.41 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.8-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.96h-1.51c-1.49 0-1.95.93-1.95 1.89v2.26h3.32l-.53 3.49h-2.79V24C19.62 23.1 24 18.1 24 12.07z" />
                    </svg>
                    Continue with Facebook
                  </>
                )}
              </button>
            </FacebookLoginButton>
          </div>

          <p className="signup-prompt">
            Don't have an account? <a href="/Register">Sign Up</a>
          </p>
        </div>

        <div className="login-info-container">
          <div className="info-content">
            <span className="info-badge">Travel with us</span>
            <h1>Explore the World</h1>
            <p>Join our community of travelers and discover amazing destinations around the globe. Book your next adventure with us!</p>
            <div className="info-stats">
              <div className="stat">
                <span className="stat-number">50k+</span>
                <span className="stat-label">Happy Travelers</span>
              </div>
              <div className="stat">
                <span className="stat-number">120+</span>
                <span className="stat-label">Destinations</span>
              </div>
              <div className="stat">
                <span className="stat-number">24/7</span>
                <span className="stat-label">Support</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Login;