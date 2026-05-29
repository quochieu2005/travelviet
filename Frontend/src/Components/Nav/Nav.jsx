import React, { useContext, useEffect, useState } from 'react'
import { CartContext } from './../../context/CartContext'

function Nav() {

    const [isMenuOpen, setIsMenuOpen] = useState(false);
    // const [ cartItems ] = useContext(CartContext);
    const [cartCount, setCartCount] = useState(0);

    useEffect(() => {
        const updateeCount = () => {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            setCartCount(cart.length);
        }

        updateeCount();

        window.addEventListener('cartUpdate', updateeCount);
        window.addEventListener('storage', updateeCount);

        return () => {
            window.removeEventListener('cartUpdate', updateeCount);
            window.removeEventListener('storage', updateeCount);
        }
    }, []);

    const toggleMenu = () => setIsMenuOpen(prev => !prev);
    const closeMenu = () => setIsMenuOpen(false);

    return (
        <nav className='text-white p-0 navbar navbar-expand-lg flex-column' style={{ backgroundColor: '#12151e' }}>
            <div className="container d-flex align-items-center justify-content-center">
                <div className="row w-100 py-3" style={{ borderBottom: '1px solid rgba(248, 250, 252, 0.08)' }}>
                    <div className="col-lg-12">
                        <div className="w-100 d-flex align-items-center justify-content-between">
                            {/* BÊN TRÁI - Call Anytime */}
                            <div className="d-flex align-items-center gap-2">
                                <span className="bi bi-telephone me-3" style={{ backgroundColor: "#222839", padding: '8px', borderRadius: '50%' }}></span>
                                <div className="call-text">
                                    <p className="m-0">Call Anytime</p>
                                    <h4 className="fs-6 m-0 fw-semibold">00 (888) +123456</h4>
                                </div>
                            </div>

                            {/* Ở GIỮA - Logo */}
                            <div className="logo">
                                <h1 className='p-0 m-0 text-uppercase fw-semibold'>
                                    <a href="/" className='text-white text-decoration-none navbar-brand fs-2 m-0'>
                                        Travel<span style={{ color: '#f26f55' }}>Viet</span>
                                    </a>
                                </h1>
                            </div>

                            {/* BÊN PHẢI - English, Cart, Sign Up */}
                            <div className="top-header-right d-none d-lg-flex align-items-center gap-4">
                                <div className="lang d-flex align-items-center gap-2 fs-6">
                                    <span className="ri-global-line"></span>
                                    <p className="m-0">English</p>
                                </div>
                                <div className="divider gradient-divider"></div>

                                <a href="/cart" className='cartpage-cart-link position-relative'>
                                    <i className="bi bi-cart text-white fs-5"></i>
                                    <span className="cart-count">
                                        {cartCount}
                                    </span>
                                </a>

                                <button className='btn sign-up btn-custome text-white rounded-5 px-4 py-2 fs-6 fw-semibold'>Sign Up</button>
                            </div>

                            {/* Nút toggle menu cho mobile */}
                            <button className="navbar-toggler nav-toggle d-block d-lg-none box-shadow-none"
                                type='button'
                                onClick={toggleMenu}
                                aria-label='Toggle navigation'
                            >
                                <span className="bi bi-list fs-1 text-white"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div className='container'>
                <div className="row py-0 py-lg-4 w-100 d-flex align-items-center">
                    <div className="col-lg-9">
                        <div className={`collapse navbar-collapse ${isMenuOpen ? 'show' : ''}`} id='navtoggle'>
                            <ul className='nav-menu list-unstyled m-0 d-flex flex-column flex-lg-row align-items-start align-tems-lg-center gap-3 gap-xl-5 gap-lg-4'>
                                <li className="nav-items position-relative">
                                    <a href="/" className='nav-link' onClick={closeMenu}>Home</a>
                                </li>

                                <li className="nav-items position-relative">
                                    <a href="#" className='nav-link' onClick={closeMenu}>Tours</a>
                                </li>

                                <li className="nav-items position-relative">
                                    <a href="#" className='nav-link' onClick={closeMenu}>Hotels</a>
                                </li>

                                <li className="nav-items position-relative">
                                    <a href="#" className='nav-link' onClick={closeMenu}>Transports</a>
                                </li>

                                <li className="nav-items position-relative">
                                    <a href="#" className='nav-link' onClick={closeMenu}>Restaurants</a>
                                </li>

                                <li className="nav-items position-relative">
                                    <a href="#" className='nav-link' onClick={closeMenu}>About</a>
                                </li>

                                <li className="nav-items position-relative">
                                    <a href="#" className='nav-link' onClick={closeMenu}>New</a>
                                </li>

                                <li className="nav-items position-relative">
                                    <a href="#" className='nav-link' onClick={closeMenu}>Contact</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div className="col-lg-3">
                        <div className="nav-input-box w-100 d-none d-lg-flex align-items-center justify-content-start gap-2">
                            <i className="bi bi-search"></i>
                            <input type="text" className='form-control form-control-sm w-100' name="" id="" placeholder='Destinations, Attraction' />
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    )
}

export default Nav