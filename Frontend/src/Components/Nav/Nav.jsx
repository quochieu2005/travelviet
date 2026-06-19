import React, { useEffect, useState, useContext } from 'react'
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import { useAuth } from '../../context/AuthContext';
import { CartContext } from '../../context/CartContext'; // ✅ thêm

function Nav() {
    const navigate = useNavigate();
    const { user, logout } = useAuth();
    const { cartItems } = useContext(CartContext); // ✅ lấy trực tiếp từ context

    const [isMenuOpen, setIsMenuOpen] = useState(false);

    // ✅ cartCount tính trực tiếp từ cartItems — reactive, không cần useEffect
    const cartCount = cartItems.length;

    const toggleMenu = () => setIsMenuOpen(prev => !prev);
    const closeMenu = () => setIsMenuOpen(false);
    const getInitial = (name) => name ? name.charAt(0).toUpperCase() : '?';

    const handleNavigate = (path) => { navigate(path); closeMenu(); };
    const handleLoginClick = () => { navigate('/Login'); closeMenu(); };
    const handleProfileClick = () => { navigate('/profile'); closeMenu(); };
    const handleLogoutClick = () => {
        logout();
        toast.success('Logged out successfully!');
        navigate('/');
        closeMenu();
    };

    return (
        <nav className='text-white p-0 navbar navbar-expand-lg flex-column' style={{ backgroundColor: '#12151e' }}>
            <div className="container d-flex align-items-center justify-content-center">
                <div className="row w-100 py-3" style={{ borderBottom: '1px solid rgba(248, 250, 252, 0.08)' }}>
                    <div className="col-lg-12">
                        <div className="w-100 d-flex align-items-center justify-content-between">

                            {/* BÊN TRÁI */}
                            <div className="d-flex align-items-center gap-2">
                                <span className="bi bi-telephone me-3" style={{ backgroundColor: "#222839", padding: '8px', borderRadius: '50%' }}></span>
                                <div className="call-text">
                                    <p className="m-0">Call Anytime</p>
                                    <h4 className="fs-6 m-0 fw-semibold">0364395437</h4>
                                </div>
                            </div>

                            {/* LOGO */}
                            <div className="logo">
                                <h1 className='p-0 m-0 text-uppercase fw-semibold'>
                                    <button
                                        onClick={() => handleNavigate('/')}
                                        className='text-white text-decoration-none navbar-brand fs-2 m-0'
                                        style={{ background: 'none', border: 'none', cursor: 'pointer' }}
                                    >
                                        Travel<span style={{ color: '#f26f55' }}>Viet</span>
                                    </button>
                                </h1>
                            </div>

                            {/* BÊN PHẢI */}
                            <div className="top-header-right d-none d-lg-flex align-items-center gap-4">
                                <div className="lang d-flex align-items-center gap-2 fs-6">
                                    <span className="ri-global-line"></span>
                                    <p className="m-0">English</p>
                                </div>
                                <div className="divider gradient-divider"></div>

                                <button
                                    onClick={() => handleNavigate('/cart')}
                                    className='cartpage-cart-link position-relative'
                                    style={{ background: 'none', border: 'none', cursor: 'pointer' }}
                                >
                                    <i className="bi bi-cart text-white fs-5"></i>
                                    {cartCount > 0 && (
                                        <span className="cart-count">{cartCount}</span>
                                    )}
                                </button>

                                {!user ? (
                                    <button
                                        onClick={handleLoginClick}
                                        className='btn sign-up btn-custome text-white rounded-5 px-4 py-2 fs-6 fw-semibold'
                                        style={{ border: 'none', cursor: 'pointer' }}
                                    >
                                        Log In
                                    </button>
                                ) : (
                                    <button
                                        onClick={handleProfileClick}
                                        className="d-flex align-items-center gap-2 text-white text-decoration-none"
                                        style={{ background: 'none', border: 'none', cursor: 'pointer' }}
                                    >
                                        <div style={{
                                            width: 36, height: 36, borderRadius: '50%',
                                            backgroundColor: '#f26f55', display: 'flex',
                                            alignItems: 'center', justifyContent: 'center',
                                            fontWeight: 700, fontSize: 15, color: 'white', flexShrink: 0
                                        }}>
                                            {getInitial(user.full_name)}
                                        </div>
                                    </button>
                                )}
                            </div>

                            {/* Mobile toggle */}
                            <button className="navbar-toggler nav-toggle d-block d-lg-none box-shadow-none"
                                type='button' onClick={toggleMenu} aria-label='Toggle navigation'>
                                <span className="bi bi-list fs-1 text-white"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div className='container'>
                <div className="row py-0 py-lg-4 w-100 d-flex align-items-center">
                    <div className="col-lg-9">
                        <div className={`collapse navbar-collapse ${isMenuOpen ? 'show' : ''}`}>
                            <ul className='nav-menu list-unstyled m-0 d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-3 gap-xl-5 gap-lg-4'>
                                <li className="nav-items position-relative">
                                    <button onClick={() => handleNavigate('/')} className='nav-link'>Home</button>
                                </li>
                                <li className="nav-items position-relative">
                                    <button onClick={() => handleNavigate('/Tours')} className='nav-link'>Tours</button>
                                </li>
                                <li className="nav-items position-relative">
                                    <button onClick={() => handleNavigate('/Hotels')} className='nav-link'>Hotels</button>
                                </li>
                                <li className="nav-items position-relative">
                                    <button onClick={() => handleNavigate('/Transports')} className='nav-link'>Transports</button>
                                </li>
                                <li className="nav-items position-relative">
                                    <button onClick={() => handleNavigate('/Restaurants')} className='nav-link'>Restaurants</button>
                                </li>
                                <li className="nav-items position-relative">
                                    <button onClick={() => handleNavigate('/About')} className='nav-link'>About</button>
                                </li>
                                <li className="nav-items position-relative">
                                    <button onClick={() => handleNavigate('/Blog')} className='nav-link'>New</button>
                                </li>
                                <li className="nav-items position-relative">
                                    <button onClick={() => handleNavigate('/Contact')} className='nav-link'>Contact</button>
                                </li>
                                <li className="nav-items d-block d-lg-none">
                                    {!user ? (
                                        <button onClick={handleLoginClick} className='nav-link'>Log In</button>
                                    ) : (
                                        <button onClick={handleLogoutClick} className='nav-link'>Logout</button>
                                    )}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div className="col-lg-3">
                        <div className="nav-input-box w-100 d-none d-lg-flex align-items-center justify-content-start gap-2">
                            <i className="bi bi-search"></i>
                            <input type="text" className='form-control form-control-sm w-100' placeholder='Destinations, Attraction' />
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    )
}

export default Nav