import React, { useContext, useEffect, useState } from "react";
import { Link } from "react-router-dom";

import { CartContext } from "../../context/CartContext";
import bgvideo from "../../assets/travel1.mp4"
import user1 from "../../assets/user-1.jpeg"
import user2 from "../../assets/user-2.png"
import user3 from "../../assets/user-3.png"
import user4 from "../../assets/user-4.jpeg"

import hand from "../../assets/hand.png"

import destination1 from "../../assets/destination-1.png"
import destination2 from "../../assets/destination-2.png"
import destination3 from "../../assets/destination-3.jpeg"
import destination4 from "../../assets/destination-4.png"
import destination5 from "../../assets/destination-5.png"
import destination6 from "../../assets/destination-6.png"
import destination7 from "../../assets/destination-7.png"

import Explore1 from "../../assets/explore-1.svg"
import Explore2 from "../../assets/explore-2.svg"
import Explore3 from "../../assets/explore-3.svg"
import Explore4 from "../../assets/explore-4.svg"
import Explore5 from "../../assets/explore-5.svg"

import ExploreImg1 from "../../assets/explore-img1.png"
import ExploreImg2 from "../../assets/explore-img2.png"
import ExploreImg3 from "../../assets/explore-img3.png"
import ExploreImg4 from "../../assets/explore-img4.png"
import ExploreImg5 from "../../assets/explore-img5.png"

import aboutbanner from "../../assets/about-banner-three.png"

import tst from "../../assets/testimonial-1.jpeg"
import tstbanner from "../../assets/testimonial-three-banner.png"

import brand1 from "../../assets/brand-1.jpeg"
import brand2 from "../../assets/brand-2.jpeg"
import brand3 from "../../assets/brand-3.jpeg"
import brand4 from "../../assets/brand-4.png"
import brand5 from "../../assets/brand-5.png"

import news4 from "../../assets/news-4.png"
import news5 from "../../assets/news-5.png"
import news6 from "../../assets/news-6.png"

import tourData from '../../data/Tour.json'

import { Swiper, SwiperSlide } from 'swiper/react'
import 'swiper/css'


function Index() {

    const [tours, setTours] = useState();
    const [visibleCount, setVisibleCount] = useState();
    // const {cartItems , addToCart} = useContext(CartContext);
    const imageModules = import.meta.glob('../../assets/*.{png,jpg,jpeg}', { eager: true });


    const getImage = (imagePath) => {
        const fileName = imagePath.split('/').pop(); // "/Image/image1.jpeg" → "image1.jpeg"
        return imageModules[`../../assets/${fileName}`]?.default || '';
    };

    useEffect(() => {
        setTours(tourData.Tours); // Trỏ vào mảng Tours bên trong object
    }, []);

    const handleBookNow = (tour) => {
        // const alreadyInCart = cartItems.find((item) => item.id === tour.id);

        // if (alreadyInCart) {
        //     alert("Tour already in cart!")
        // }
        // else {
        //     addToCart({ ...tour, quantity: 1 });
        //     alert("Added")
        // }
        alert(`Added ${tour.title} to cart!`)
    }

    const [activeIndex, setActiveIndex] = useState(0);

    const tabs = [
        { title: "Fishing & Swimming", img: Explore1, ExploreImg: ExploreImg1 },
        { title: "Boating & Kayaking", img: Explore2, ExploreImg: ExploreImg2 },
        { title: "Trailers & Sports", img: Explore3, ExploreImg: ExploreImg3 },
        { title: "Mountain & Hill Hiking", img: Explore4, ExploreImg: ExploreImg4 },
        { title: "Paragliding Tours", img: Explore5, ExploreImg: ExploreImg5 },
        { title: "Music & Relaxing", img: Explore1, ExploreImg: ExploreImg1 },
        { title: "Mountain & Hill Hiking", img: Explore3, ExploreImg: ExploreImg3 },
        { title: "Fishing & Swimming", img: Explore1, ExploreImg: ExploreImg1 },
    ]

    return (
        <>
            <div className="hero-header section">
                <video
                    autoPlay
                    muted
                    loop
                    playsInline
                    className="hero-video"
                >
                    <source src={bgvideo} type="video/mp4" />
                </video>

                <div className="hero-overlay text-white">
                    <div className="container">
                        <div className="row">
                            <div className="col-xl-6">
                                <h1 className="hero-title">Plan Tours to dream<br />locations in just a click!</h1>
                                <p className="hero-description">Travel is aa transformative and enriching experience that
                                    cultures, and landscapes.
                                </p>

                                <div className="d-flex align-items-center">

                                    <div className="users">
                                        <img src={user1} className="user-img" alt="user-image" />
                                        <img src={user2} className="user-img" alt="user-image" />
                                        <img src={user3} className="user-img" alt="user-image" />
                                        <img src={user4} className="user-img" alt="user-image" />
                                        <span>5k +</span>
                                    </div>

                                    <p className="m-0 px-3 fs-6 fw-semibold">Happy Customer</p>
                                    <img src={hand} className="img-fluid" alt="hand-image" width={40} height={40} />

                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="container w-100 travel-box p-4 bg-dark text-white rounded z-0">
                        <div className="row align-items-center justify-content-between w-100 gap-4 gap-xl-0">
                            <div className="col-xl-2 travel-info" style={{ borderRight: '1px solid rgba(248, 250 , 252, 0.08)' }}>
                                <label className="form-label fw-semibold fs-5 text-white">
                                    <i className="bi bi-geo-alt-fill me-2 fs-6"></i>
                                    Destination
                                </label>

                                <select className="form-select bg-dark text-white border-secondary border-0">
                                    <option>Chittagong, Turkish</option>
                                    <option>Dhaka, Bangladesh</option>
                                    <option>Istanbul, Turkey</option>
                                </select>
                            </div>

                            <div className="col-xl-2 travel-info" style={{ borderRight: '1px solid rgba(248, 250 , 252, 0.08)' }}>
                                <label className="form-label fw-semibold fs-5 text-white">
                                    <i className="bi bi-airplane me-2 fs-6"></i>
                                    Tour Type
                                </label>

                                <select className="form-select bg-dark text-white border-secondary border-0">
                                    <option>Pre-book Type</option>
                                    <option>Instant Booking</option>
                                    <option>Custom Package</option>
                                </select>
                            </div>

                            <div className="col-xl-2 travel-info" style={{ borderRight: '1px solid rgba(248, 250 , 252, 0.08)' }}>
                                <label className="form-label fw-semibold fs-5 text-white">
                                    <i className="bi bi-clock me-2 fs-6"></i>
                                    Date From
                                </label>

                                <input
                                    type="date"
                                    id="datepicker"
                                    className="form-control bg-dark text-white border-0"
                                />
                            </div>

                            <div className="col-xl-2 travel-info" style={{ borderRight: '1px solid rgba(248, 250 , 252, 0.08)' }}>
                                <label className="form-label fw-semibold fs-5 text-white">
                                    <i className="bi bi-person me-2 fs-6"></i>
                                    Guests
                                </label>

                                <select className="form-select bg-dark text-white border-0">
                                    <option>02</option>
                                    <option>01</option>
                                    <option>03</option>
                                    <option>04+</option>
                                </select>
                            </div>

                            <div className="col-xl-2 travel-info" style={{ borderRight: '1px solid rgba(248, 250 , 252, 0.08)' }}>
                                <button className="travel-btn py-3 px-5 fs-6 btn btn-primary fw-semibold" style={{ backgroundColor: '#f26f55', border: 'none', cursor: 'pointer' }}>
                                    Search Plan
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {/* Banner */}
            <div className="banner-container section">
                <div className="container">
                    <div className="row text-center mb-5">
                        <div className="section-title">
                            <p>Special offers</p>
                            <h2>Winter Our Big Offers To Inspire You</h2>
                        </div>
                    </div>
                </div>

                <div className="container">
                    <div className="row">
                        <div className="col-lg-6 mb-4">
                            <div className="banner-content z-1 py-5 px-4 rounded-3 banner-bg-1 text-white">
                                <p className="highlight-text">Save Up To</p>
                                <h4 className="fs-1 fw-semibold">50%</h4>
                                <p className="pera fs-4 fw-bold">Let's Explor the World</p>
                                <div className="location d-flex align-items-center gap-2">
                                    <i className="bi bi-geo-alt"></i>
                                    <p className="name mb-0">Bangkok, ThaiLand</p>
                                </div>

                                <button className="btn banner-btn px-4">Booking now</button>
                            </div>
                        </div>

                        <div className="col-lg-6 mb-4">
                            <div className="banner-content z-1 py-5 px-4 rounded-3 banner-bg-2 text-white">
                                <h4 className="fs-1 fw-semibold">Nearby Hotel</h4>
                                <p className="pera">
                                    Up to <span className="highlights-text">50%</span> Off
                                </p>

                                <div className="location d-flex align-items-center gap-2">
                                    <i className="bi bi-geo-alt"></i>
                                    <p className="name mb-0">Bangkok, ThaiLand</p>
                                </div>

                                <button className="btn banner-btn px-4">Booking now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Tours */}
            <div className="tours-container section">
                <div className="container">
                    <div className="row text-center mb-5">
                        <div className="section-title d-flex align-items-center flex-column">
                            <p>Features Tours</p>
                            <h2>Most Favorite Tour Place
                                <br />
                                in the world
                            </h2>
                        </div>
                    </div>
                </div>

                <div className="container">
                    <div className="row">
                        <Swiper
                            slidesPerView={4}
                            spaceBetween={20}
                            breakpoints={{
                                1399: { slidesPerView: 4 },
                                1199: { slidesPerView: 3 },
                                991: { slidesPerView: 2 },
                                767: { slidesPerView: 1.5 },
                                0: { slidesPerView: 1 },
                            }}
                            className="mt-4 swiper position-relative"
                        >
                            {tours && tours.filter(tour => tour.id >= 19 && tour.id <= 25)
                                .map(tour => (
                                    <SwiperSlide key={tour.id}>
                                        <div className="card h-100 tour-card shadow-sm position-relative">
                                            <div className="position-relative">
                                                <Link to = {`/TourDetail/${tour.slug}` }>
                                                    <img src={getImage(tour.image)} className="card-img-top rounded" alt={tour.title} />
                                                </Link>
                                                <i className="ri-shopping-cart-2-line fs-5 text-white position-absolute top-0 end-0 m-2"
                                                    role="button"
                                                    onClick={() => handleBookNow(tour)}
                                                    style={{ cursor: 'pointer', backgroundColor: 'rgba(0,0,0,0.5)', padding: '8px', borderRadius: '50%' }}
                                                ></i>
                                            </div>

                                            <div className="card-body py-3">
                                                <h5 className="card-title fw-semibold mb-1">{tour.title}</h5>
                                                <p className="mb-3"><i className="ri-map-pin-line"></i> {tour.location}</p>
                                                <div className="d-flex flex-wrap justify-content-between small mb-3 p-2 rounded" style={{ backgroundColor: 'rgba( 248, 250 , 252 , .08)' }}>
                                                    <div className="me-3"><i className="ri-time-line me-1"></i>{tour.duration}</div>
                                                    <div><i className="ri-user-line me-1"></i>{tour.max_people} người</div>
                                                </div>

                                                {/* Phần hiển thị giá - giữ nguyên chữ FROM */}
                                                <div className="d-flex mt-2 align-items-center justify-content-between">
                                                    <div className="ms-1" style={{ color: '#8f94a3' }}>
                                                        From
                                                        <span className="text-warning fw-bold ms-1 fs-5">
                                                            {tour['discount price'].toLocaleString('vi-VN')}₫
                                                        </span>
                                                    </div>
                                                    {tour['price discount percent'] > 0 && (
                                                        <span className="badge bg-danger rounded-pill me-1">
                                                            -{tour['price discount percent']}%
                                                        </span>
                                                    )}
                                                </div>

                                                {/* Hiển thị giá gốc đã gạch nếu có giảm giá */}
                                                {tour['price discount percent'] > 0 && (
                                                    <div className="small mt-1 ms-1">
                                                        <span className="text-muted text-decoration-line-through">
                                                            {tour['price adult'].toLocaleString('vi-VN')}₫
                                                        </span>
                                                    </div>
                                                )}

                                                {/* Hiển thị giá trẻ em */}
                                                <div className="small mt-2" style={{ color: '#8f94a3' }}>
                                                    <i className="ri-user-star-line me-1"></i>
                                                    Trẻ em: {tour['discount price child'].toLocaleString('vi-VN')}₫
                                                    {tour['price child discount percent'] > 0 && (
                                                        <span className="text-muted text-decoration-line-through ms-1 small">
                                                            {tour['price child'].toLocaleString('vi-VN')}₫
                                                        </span>
                                                    )}
                                                    {tour['price child discount percent'] > 0 && (
                                                        <span className="ms-1 text-danger small">(-{tour['price child discount percent']}%)</span>
                                                    )}
                                                </div>

                                                {/* Hiển thị rating và review */}
                                                <div className="d-flex align-items-center mt-2 ms-1">
                                                    <div className="text-warning me-2">
                                                        {"★".repeat(Math.floor(tour.rating))}
                                                        {tour.rating % 1 !== 0 && "½"}
                                                        {"☆".repeat(5 - Math.ceil(tour.rating))}
                                                    </div>
                                                    <span className="small text-muted">({tour.review} đánh giá)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </SwiperSlide>
                                ))
                            }
                        </Swiper>
                    </div>
                </div>
            </div>
        </>
    )
}

export default Index