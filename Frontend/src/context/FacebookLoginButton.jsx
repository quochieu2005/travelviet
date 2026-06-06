const FacebookLoginButton = ({ onSuccess, onError, children, disabled }) => {

  const handleLogin = () => {
    if (disabled || !window.FB) {
      console.error('Facebook SDK not loaded');
      return;
    }

    window.FB.login((response) => {
      if (response.authResponse) {
        onSuccess({
          accessToken: response.authResponse.accessToken,
          userID: response.authResponse.userID,
          expiresIn: response.authResponse.expiresIn
        });
      } else {
        onError(response.error || 'User cancelled login.');
      }
    }, {
      scope: 'email,public_profile',
      return_scopes: true
    });
  };

  return (
    <div onClick={handleLogin} style={{ cursor: disabled ? 'not-allowed' : 'pointer' }}>
      {children}
    </div>
  );
};

export default FacebookLoginButton;