import React, { useContext, useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { useLoginToast } from '../../hooks/useLoginToast';

import { CartContext } from "../../context/CartContext";
import bgvideo from "../../assets/travel1.mp4"
import user1 from "../../assets/user-1.jpeg"
import user2 from "../../assets/user-2.png"
import user3 from "../../assets/user-3.png"
import user4 from "../../assets/user-4.jpeg"
import hand from "../../assets/hand.png"

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

import { Swiper, SwiperSlide } from 'swiper/react'
import 'swiper/css'
import { tourService } from "../../services/tourService";
import { getPricingPlans, submitPricingInquiry } from '../../services/aboutApi';

function Index() {

    const [tours, setTours] = useState([]);
    const [destinations, setDestinations] = useState([]);
    const [categories, setCategories] = useState([]);
    const [destinationsMap, setDestinationsMap] = useState({});
    const [testimonials, setTestimonials] = useState([]);
    const [blogs, setBlogs] = useState([]);
    const [brands, setBrands] = useState([]);
    const [tourImages, setTourImages] = useState({});
    const [loading, setLoading] = useState(true);
    const [plans, setPlans] = useState([]);
    const [selectedPlan, setSelectedPlan] = useState(null);
    const [showModal, setShowModal] = useState(false);
    const [form, setForm] = useState({ name: '', email: '', phone: '', message: '' });
    const [submitting, setSubmitting] = useState(false);
    const [toastMsg, setToastMsg] = useState(null);
    const [filters, setFilters] = useState({
        destination: '',
        tourType: '',
        dateFrom: '',
        guests: ''
    });

    // State cho random data
    const [randomDestinations, setRandomDestinations] = useState([]);
    const [randomFeaturedTours, setRandomFeaturedTours] = useState([]);
    const [randomWinterTours, setRandomWinterTours] = useState([]);

    const { cartItems, addTOCart } = useContext(CartContext);
    const imageModules = import.meta.glob('../../assets/*.{png,jpg,jpeg}', { eager: true });

    const getImage = (imagePath) => {
        if (!imagePath) return '';
        const fileName = imagePath?.split('/').pop();
        return imageModules[`../../assets/${fileName}`]?.default || '';
    };

    const getTourImage = (tour) => {
        if (tourImages[tour.id]) {
            if (tourImages[tour.id].startsWith('http') || tourImages[tour.id].startsWith('/storage')) {
                return tourImages[tour.id];
            }
            return getImage(tourImages[tour.id]);
        }
        if (tour.image) {
            return getImage(tour.image);
        }
        return getImage('destination-1.png');
    };

    const getDestinationName = (destinationId) => {
        return destinationsMap[destinationId] || 'Đang cập nhật';
    };

    const formatPrice = (price) => {
        if (!price && price !== 0) return '0đ';
        return price.toLocaleString('vi-VN') + 'đ';
    };

    const calculateDiscount = (originalPrice, discountValue) => {
        if (!discountValue || discountValue === 0 || !originalPrice || originalPrice <= 0) {
            return {
                finalPrice: originalPrice,
                percent: 0,
                amount: 0
            };
        }

        let finalPrice, percent, amount;

        if (discountValue < 100) {
            percent = discountValue;
            amount = (originalPrice * discountValue) / 100;
            finalPrice = originalPrice - amount;
        } else {
            amount = discountValue;
            percent = (discountValue / originalPrice) * 100;
            finalPrice = originalPrice - discountValue;
        }

        return {
            finalPrice: Math.round(finalPrice),
            percent: Math.round(percent),
            amount: Math.round(amount)
        };
    };

    // Hàm random lấy phần tử từ mảng (không giới hạn theo id)
    const getRandomItems = (arr, maxCount = 10) => {
        if (!arr || arr.length === 0) return [];
        const shuffled = [...arr];
        for (let i = shuffled.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
        }
        return shuffled.slice(0, Math.min(maxCount, shuffled.length));
    };

    // Hàm random lấy 2 mảng tours khác nhau, không trùng nhau
    const getUniqueRandomTours = (allTours, count1 = 10, count2 = 10) => {
        if (!allTours || allTours.length === 0) return [[], []];
        
        // Random toàn bộ mảng tours
        const shuffled = [...allTours];
        for (let i = shuffled.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
        }
        
        // Lấy số lượng cần cho tour 1
        const tours1 = shuffled.slice(0, Math.min(count1, shuffled.length));
        
        // Lấy phần còn lại cho tour 2 (không trùng)
        const remaining = shuffled.slice(Math.min(count1, shuffled.length));
        const tours2 = remaining.slice(0, Math.min(count2, remaining.length));
        
        return [tours1, tours2];
    };

    const showToast = (type, message) => {
        setToastMsg({ type, message });
        setTimeout(() => setToastMsg(null), 4000);
    };

    const handleTryNow = (plan) => {
        setSelectedPlan(plan);
        setForm({ name: '', email: '', phone: '', message: '' });
        setShowModal(true);
    };

    const handleCloseModal = () => {
        setShowModal(false);
        setSelectedPlan(null);
    };

    const handleSubmitInquiry = async (e) => {
        e.preventDefault();
        setSubmitting(true);
        try {
            const data = await submitPricingInquiry({
                pricing_plan_id: selectedPlan.id,
                ...form,
            });
            if (data.success) {
                setShowModal(false);
                showToast('success', data.message);
            } else {
                showToast('error', 'Có lỗi xảy ra, vui lòng thử lại.');
            }
        } catch (error) {
            showToast('error', 'Không thể kết nối server.');
        } finally {
            setSubmitting(false);
        }
    };

    useEffect(() => {
        fetchHomeData();
        fetchPricingPlans();
    }, []);

    // Cập nhật random data khi destinations hoặc tours thay đổi
    useEffect(() => {
        if (destinations.length > 0) {
            // Random destinations lấy tối đa 7 cái, random hoàn toàn không theo id
            setRandomDestinations(getRandomItems(destinations, 7));
        }
    }, [destinations]);

    useEffect(() => {
        if (tours.length > 0) {
            // Random 2 mảng tours khác nhau, mỗi mảng tối đa 10 cái, không trùng nhau
            const [featured, winter] = getUniqueRandomTours(tours, 10, 10);
            setRandomFeaturedTours(featured);
            setRandomWinterTours(winter);
        }
    }, [tours]);

    const fetchHomeData = async () => {
        try {
            setLoading(true);
            const response = await tourService.getHomeData();

            if (response.success) {
                const destinationsMapData = {};
                (response.destinations || []).forEach(dest => {
                    destinationsMapData[dest.id] = dest.name;
                });
                setDestinationsMap(destinationsMapData);
                setDestinations(response.destinations || []);
                setCategories(response.categories || []);
                setTestimonials(response.testimonials || []);
                setBlogs(response.blogs || []);
                setBrands(response.brands || []);

                const processedTours = (response.tours || []).map(tour => {
                    const priceAdult = parseFloat(tour.price_adult) || 0;
                    const priceChild = parseFloat(tour.price_child) || 0;
                    const discountAdultRaw = parseFloat(tour.price_discount_percent) || 0;
                    const discountChildRaw = parseFloat(tour.price_child_discount_percent) || 0;

                    const adultDiscount = calculateDiscount(priceAdult, discountAdultRaw);
                    const childDiscount = calculateDiscount(priceChild, discountChildRaw);

                    return {
                        ...tour,
                        price_adult: priceAdult,
                        price_child: priceChild,
                        discount_price: adultDiscount.finalPrice,
                        discount_price_child: childDiscount.finalPrice,
                        discount_percent: adultDiscount.percent,
                        discount_percent_child: childDiscount.percent,
                        discount_amount: adultDiscount.amount,
                        discount_amount_child: childDiscount.amount
                    };
                });

                setTours(processedTours);

                if (response.tourImages && response.tourImages.length > 0) {
                    const imagesMap = {};
                    response.tourImages.forEach(img => {
                        imagesMap[img.tour_id] = img.image || img.image_url;
                    });
                    setTourImages(imagesMap);
                }
            }
        } catch (error) {
            toast.error("Không thể tải dữ liệu từ server!");
        } finally {
            setLoading(false);
        }
    };

    const fetchPricingPlans = async () => {
        try {
            const data = await getPricingPlans();
            if (data.success && data.data) {
                setPlans(data.data);
            }
        } catch (error) {
            console.error('Error fetching pricing plans:', error);
        }
    };

    const handleBookNow = (tour) => {
        const alreadyInCart = cartItems.find((item) => item.id === tour.id);
        if (alreadyInCart) {
            toast.warning("Tour đã có trong giỏ hàng!");
        } else {
            addTOCart({ ...tour, quantity: 1 });
            toast.success(`Đã thêm ${tour.title} vào giỏ!`);
        }
    };

    const handleFilterChange = (e) => {
        const { name, value } = e.target;
        setFilters(prev => ({ ...prev, [name]: value }));
    };

    const handleSearch = async () => {
        try {
            setLoading(true);
            let filtered = [...tours];
            if (filters.destination) {
                filtered = filtered.filter(tour =>
                    getDestinationName(tour.destination_id).toLowerCase().includes(filters.destination.toLowerCase())
                );
            }
            if (filters.guests) {
                filtered = filtered.filter(tour =>
                    tour.max_people >= parseInt(filters.guests)
                );
            }
            setTours(filtered);
            toast.success(`Tìm thấy ${filtered.length} tour phù hợp!`);
        } catch (error) {
            toast.error("Có lỗi xảy ra khi tìm kiếm!");
        } finally {
            setLoading(false);
        }
    };

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
    ];

    useLoginToast();

    return (
        <>
            {toastMsg && (
                <div style={{
                    position: 'fixed', top: '20px', right: '20px', zIndex: 9999,
                    padding: '14px 20px', borderRadius: '8px', color: '#fff',
                    background: toastMsg.type === 'success' ? '#28a745' : '#dc3545',
                    boxShadow: '0 4px 12px rgba(0,0,0,0.2)',
                }}>
                    <i className={`ri-${toastMsg.type === 'success' ? 'checkbox-circle' : 'error-warning'}-line me-2`}></i>
                    {toastMsg.message}
                </div>
            )}

            {showModal && selectedPlan && (
                <div
                    onClick={(e) => e.target === e.currentTarget && handleCloseModal()}
                    style={{
                        position: 'fixed', inset: 0, zIndex: 9000,
                        background: 'rgba(0,0,0,0.55)',
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                        padding: '16px',
                    }}
                >
                    <div style={{
                        background: '#fff', borderRadius: '16px', padding: '32px',
                        width: '100%', maxWidth: '480px',
                        boxShadow: '0 20px 60px rgba(0,0,0,0.2)',
                    }}>
                        <div className="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h5 className="mb-1">
                                    Đăng ký gói <strong>{selectedPlan.name}</strong>
                                </h5>
                                <small className="text-muted">
                                    {selectedPlan.price
                                        ? formatPrice(selectedPlan.price)
                                        : 'Liên hệ để báo giá'}
                                    {selectedPlan.price_note && ` — ${selectedPlan.price_note}`}
                                </small>
                            </div>
                            <button onClick={handleCloseModal} style={{
                                background: 'none', border: 'none',
                                fontSize: '22px', cursor: 'pointer', color: '#888',
                            }}>
                                <i className="ri-close-line"></i>
                            </button>
                        </div>

                        <form onSubmit={handleSubmitInquiry}>
                            <div className="mb-3">
                                <label className="form-label">Họ và tên <span className="text-danger">*</span></label>
                                <input
                                    type="text" className="form-control"
                                    placeholder="Nguyễn Văn A"
                                    value={form.name}
                                    onChange={e => setForm({ ...form, name: e.target.value })}
                                    required
                                />
                            </div>
                            <div className="mb-3">
                                <label className="form-label">Email <span className="text-danger">*</span></label>
                                <input
                                    type="email" className="form-control"
                                    placeholder="example@email.com"
                                    value={form.email}
                                    onChange={e => setForm({ ...form, email: e.target.value })}
                                    required
                                />
                            </div>
                            <div className="mb-3">
                                <label className="form-label">Số điện thoại <span className="text-danger">*</span></label>
                                <input
                                    type="tel" className="form-control"
                                    placeholder="0901 234 567"
                                    value={form.phone}
                                    onChange={e => setForm({ ...form, phone: e.target.value })}
                                    required
                                />
                            </div>
                            <div className="mb-4">
                                <label className="form-label">Tin nhắn thêm</label>
                                <textarea
                                    className="form-control" rows={3}
                                    placeholder="Bạn có câu hỏi gì không?"
                                    value={form.message}
                                    onChange={e => setForm({ ...form, message: e.target.value })}
                                />
                            </div>
                            <button
                                type="submit" className="btn w-100" disabled={submitting}
                                style={{ background: 'var(--primary-color)', color: '#fff' }}
                            >
                                {submitting
                                    ? <><i className="ri-loader-4-line me-2"></i>Đang gửi...</>
                                    : <><i className="ri-send-plane-line me-2"></i>Gửi đăng ký</>
                                }
                            </button>
                        </form>
                    </div>
                </div>
            )}

            <div>
                <ToastContainer position="top-right" autoClose={2500} theme="dark" newestOnTop closeOnClick pauseOnHover />

                <div className="hero-header section">
                    <video autoPlay muted loop playsInline className="hero-video">
                        <source src={bgvideo} type="video/mp4" />
                    </video>

                    <div className="hero-overlay text-white">
                        <div className="container">
                            <div className="row">
                                <div className="col-xl-6">
                                    <h1 className="hero-title">Plan Tours to dream<br />locations in just a click!</h1>
                                    <p className="hero-description">Travel is a transformative and enriching experience that cultures, and landscapes.</p>

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
                                    <select
                                        className="form-select bg-dark text-white border-secondary border-0"
                                        name="destination"
                                        value={filters.destination}
                                        onChange={handleFilterChange}
                                    >
                                        <option value="">Chọn điểm đến</option>
                                        {destinations.map(dest => (
                                            <option key={dest.id} value={dest.name}>{dest.name}</option>
                                        ))}
                                    </select>
                                </div>

                                <div className="col-xl-2 travel-info" style={{ borderRight: '1px solid rgba(248, 250 , 252, 0.08)' }}>
                                    <label className="form-label fw-semibold fs-5 text-white">
                                        <i className="bi bi-airplane me-2 fs-6"></i>
                                        Tour Type
                                    </label>
                                    <select
                                        className="form-select bg-dark text-white border-secondary border-0"
                                        name="tourType"
                                        value={filters.tourType}
                                        onChange={handleFilterChange}
                                    >
                                        <option value="">Chọn loại tour</option>
                                        {categories.map(cat => (
                                            <option key={cat.id} value={cat.id}>
                                                {cat.name}
                                            </option>
                                        ))}
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
                                        name="dateFrom"
                                        className="form-control bg-dark text-white border-0"
                                        value={filters.dateFrom}
                                        onChange={handleFilterChange}
                                    />
                                </div>

                                <div className="col-xl-2 travel-info" style={{ borderRight: '1px solid rgba(248, 250 , 252, 0.08)' }}>
                                    <label className="form-label fw-semibold fs-5 text-white">
                                        <i className="bi bi-person me-2 fs-6"></i>
                                        Guests
                                    </label>
                                    <select
                                        className="form-select bg-dark text-white border-0"
                                        name="guests"
                                        value={filters.guests}
                                        onChange={handleFilterChange}
                                    >
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04+</option>
                                    </select>
                                </div>

                                <div className="col-xl-2 travel-info">
                                    <button
                                        className="travel-btn py-3 px-5 fs-6 btn btn-primary fw-semibold"
                                        style={{ backgroundColor: '#f26f55', border: 'none', cursor: 'pointer' }}
                                        onClick={handleSearch}
                                    >
                                        Search Plan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                    <p className="pera">Up to <span className="highlights-text">50%</span> Off</p>
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

                {/* Tours Section 1 - Random Featured Tours (không trùng với Tour 2) */}
                <div className="tours-container section">
                    <div className="container">
                        <div className="row text-center mb-5">
                            <div className="section-title d-flex align-items-center flex-column">
                                <p>Features Tours</p>
                                <h2>Most Favorite Tour Place<br />in the world</h2>
                            </div>
                        </div>
                    </div>

                    <div className="container">
                        <div className="row">
                            {randomFeaturedTours.length > 0 ? (
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
                                    loop={randomFeaturedTours.length >= 5}
                                >
                                    {randomFeaturedTours.map(tour => (
                                        <SwiperSlide key={tour.id}>
                                            <div className="card h-100 tour-card shadow-sm position-relative">
                                                <div className="position-relative">
                                                    <Link to={`/TourDetail/${tour.slug}`}>
                                                        <img
                                                            src={getTourImage(tour)}
                                                            className="card-img-top rounded"
                                                            alt={tour.title}
                                                            onError={(e) => {
                                                                e.target.onerror = null;
                                                                e.target.src = getImage('destination-1.png');
                                                            }}
                                                        />
                                                    </Link>
                                                    <i className="ri-shopping-cart-2-line fs-5 text-white position-absolute top-0 end-0 m-2"
                                                        role="button"
                                                        onClick={() => handleBookNow(tour)}
                                                        style={{ cursor: 'pointer', backgroundColor: 'rgba(0,0,0,0.5)', padding: '8px', borderRadius: '50%' }}
                                                    ></i>
                                                </div>

                                                <div className="card-body py-3">
                                                    <h5 className="card-title fw-semibold mb-1">{tour.title}</h5>
                                                    <p className="mb-3">
                                                        <i className="ri-map-pin-line"></i>
                                                        {getDestinationName(tour.destination_id)}
                                                    </p>

                                                    <div className="d-flex flex-wrap justify-content-between small mb-3 p-2 rounded" style={{ backgroundColor: 'rgba( 248, 250 , 252 , .08)' }}>
                                                        <div className="me-3"><i className="ri-time-line me-1"></i>{tour.duration_days || tour.duration} ngày</div>
                                                        <div><i className="ri-user-line me-1"></i>{tour.max_people} người</div>
                                                    </div>

                                                    <div className="d-flex mt-2 align-items-center justify-content-between">
                                                        <div className="ms-1" style={{ color: '#8f94a3' }}>
                                                            From
                                                            <span className="text-warning fw-bold ms-1 fs-5">
                                                                {formatPrice(tour.discount_price)}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    {tour.discount_amount > 0 && (
                                                        <div className="small mt-1 ms-1">
                                                            <span className="text-muted text-decoration-line-through">
                                                                {formatPrice(tour.price_adult)}
                                                            </span>
                                                            <span className="ms-2 text-danger">
                                                                (- {formatPrice(tour.discount_amount)})
                                                            </span>
                                                        </div>
                                                    )}

                                                    <div className="small mt-2" style={{ color: '#8f94a3' }}>
                                                        <i className="ri-user-star-line me-1"></i>
                                                        Trẻ em: {formatPrice(tour.discount_price_child)}
                                                        {tour.discount_amount_child > 0 && (
                                                            <>
                                                                <span className="text-muted text-decoration-line-through ms-1 small">
                                                                    {formatPrice(tour.price_child)}
                                                                </span>
                                                                <span className="ms-1 text-danger small">
                                                                    (-{tour.discount_percent_child}%)
                                                                </span>
                                                            </>
                                                        )}
                                                    </div>

                                                    <div className="d-flex align-items-center mt-2 ms-1">
                                                        <div className="text-warning me-2">
                                                            {"★".repeat(Math.floor(tour.rating || 4.5))}
                                                            {(tour.rating || 4.5) % 1 !== 0 && "½"}
                                                            {"☆".repeat(5 - Math.ceil(tour.rating || 4.5))}
                                                        </div>
                                                        <span className="small text-muted">({tour.review || 0} đánh giá)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </SwiperSlide>
                                    ))}
                                </Swiper>
                            ) : (
                                <div className="text-center py-5">
                                    <p>Đang tải dữ liệu tour...</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* About */}
                <div className="about-section section">
                    <div className="container about">
                        <div className="row">
                            <div className="col-lg-6">
                                <div className="section-title">
                                    <div className="row">
                                        <p>Special Offers</p>
                                        <h2>Get The Best Travel Experience With TravelViet</h2>
                                    </div>
                                </div>
                                <p className="about-pera">Travel is a transformative and enriching experience that allows individuals to explore new destinations cultures, and landscapes.it is a fundamental human activity that has been practiced for centuries and continue to be a source of joy. learning, and personal growth.</p>
                                <p className="about-pera">Travel is a transformative and enriching experience that allows individuals to explore new destinations cultures...</p>
                                <button className="btn">Learn More <i className="ri-arrow-right-up-line"></i></button>
                                <div className="user-icon d-flex align-items-center gap-3 mt-4">
                                    <i className="ri-user-line"></i>
                                    <p className="about-pera m-0">2,500 People Booked Tomorrow Land Event in the last 24 hours</p>
                                </div>
                            </div>

                            <div className="col-lg-6 mt-xl-0 mt-5">
                                <div className="about-img">
                                    <img src={aboutbanner} alt="about-image" className="img-fluid rounded-4" />
                                </div>
                                <div className="row stats-box mt-5 text-center">
                                    <div className="col-md-4 mb-3">
                                        <h4>150K</h4>
                                        <span>Happy Travel</span>
                                    </div>
                                    <div className="col-md-4 mb-3">
                                        <h4>95.7%</h4>
                                        <span>Satisfaction Rate</span>
                                    </div>
                                    <div className="col-md-4 mb-3">
                                        <h4>5000+</h4>
                                        <span>Tour Completed</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Tours Section 2 - Random Winter Tours (không trùng với Tour 1) */}
                <div className="tours-container section tours-container-2 position-relative">
                    <div className="container">
                        <div className="row text-center mb-5">
                            <div className="section-title d-flex align-items-center flex-column">
                                <p>Winter Offers</p>
                                <h2>Special Winter Tour Deals<br />For You</h2>
                            </div>
                        </div>
                    </div>

                    <div className="container">
                        <div className="row">
                            {randomWinterTours.length > 0 ? (
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
                                    loop={randomWinterTours.length >= 5}
                                >
                                    {randomWinterTours.map(tour => (
                                        <SwiperSlide key={tour.id}>
                                            <div className="card h-100 tour-card shadow-sm position-relative">
                                                <div className="position-relative">
                                                    <Link to={`/TourDetail/${tour.slug}`}>
                                                        <img src={getTourImage(tour)} className="card-img-top rounded" alt={tour.title} />
                                                    </Link>
                                                    <i className="ri-shopping-cart-2-line fs-5 text-white position-absolute top-0 end-0 m-2"
                                                        role="button"
                                                        onClick={() => handleBookNow(tour)}
                                                        style={{ cursor: 'pointer', backgroundColor: 'rgba(0,0,0,0.5)', padding: '8px', borderRadius: '50%' }}
                                                    ></i>
                                                </div>

                                                <div className="card-body py-3">
                                                    <h5 className="card-title fw-semibold mb-1">{tour.title}</h5>
                                                    <p className="mb-3"><i className="ri-map-pin-line"></i> {getDestinationName(tour.destination_id)}</p>
                                                    <div className="d-flex flex-wrap justify-content-between small mb-3 p-2 rounded" style={{ backgroundColor: 'rgba( 248, 250 , 252 , .08)' }}>
                                                        <div className="me-3"><i className="ri-time-line me-1"></i>{tour.duration_days || tour.duration} ngày</div>
                                                        <div><i className="ri-user-line me-1"></i>{tour.max_people} người</div>
                                                    </div>

                                                    <div className="d-flex mt-2 align-items-center justify-content-between">
                                                        <div className="ms-1" style={{ color: '#8f94a3' }}>
                                                            From
                                                            <span className="text-warning fw-bold ms-1 fs-5">
                                                                {formatPrice(tour.discount_price)}
                                                            </span>
                                                        </div>
                                                        {tour.discount_percent > 0 && (
                                                            <span className="badge bg-danger rounded-pill me-1">
                                                                -{tour.discount_percent}%
                                                            </span>
                                                        )}
                                                    </div>

                                                    {tour.discount_amount > 0 && (
                                                        <div className="small mt-1 ms-1">
                                                            <span className="text-muted text-decoration-line-through">
                                                                {formatPrice(tour.price_adult)}
                                                            </span>
                                                        </div>
                                                    )}

                                                    <div className="small mt-2" style={{ color: '#8f94a3' }}>
                                                        <i className="ri-user-star-line me-1"></i>
                                                        Trẻ em: {formatPrice(tour.discount_price_child)}
                                                        {tour.discount_amount_child > 0 && (
                                                            <span className="text-muted text-decoration-line-through ms-1 small">
                                                                {formatPrice(tour.price_child)}
                                                            </span>
                                                        )}
                                                        {tour.discount_percent_child > 0 && (
                                                            <span className="ms-1 text-danger small">(-{tour.discount_percent_child}%)</span>
                                                        )}
                                                    </div>

                                                    <div className="d-flex align-items-center mt-2 ms-1">
                                                        <div className="text-warning me-2">
                                                            {"★".repeat(Math.floor(tour.rating || 4.5))}
                                                            {(tour.rating || 4.5) % 1 !== 0 && "½"}
                                                            {"☆".repeat(5 - Math.ceil(tour.rating || 4.5))}
                                                        </div>
                                                        <span className="small text-muted">({tour.review || 0} đánh giá)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </SwiperSlide>
                                    ))}
                                </Swiper>
                            ) : (
                                <div className="text-center py-5">
                                    <p>Đang tải dữ liệu tour...</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Destination - Random Destinations (tối đa 7 cái, random hoàn toàn) */}
<div className="destination-container section">
    <div className="container">
        <div className="row text-center mb-5">
            <div className="section-title">
                <p>Destination List</p>
                <h2>Explore The Beautiful <br /> Places Around World</h2>
            </div>
        </div>
    </div>

    <div className="container">
        {/* 3 destination lớn ở hàng đầu */}
        <div className="row">
            {randomDestinations.slice(0, 3).map(dest => (
                <div key={dest.id} className="col-xl-4 col-lg-7 col-md-7 mb-4 mb-lg-2">
                    <div className="destination-item w-100 rounded-3 text-white">
                        <img src={dest.image} alt={dest.name} />
                        <div className="destination-info p-4 w-100">
                            <div className="destination-name">
                                <p className="pera m-0 fs-2 fw-bold">{dest.name}</p>
                                <div className="location d-flex gap-2">
                                    <i className="ri-map-pin-line"></i>
                                    <p className="name m-0">{dest.name}</p>
                                </div>
                            </div>
                            <div className="arrow-btn">
                                <i className="ri-arrow-right-line fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            ))}
        </div>

        {/* 4 destination nhỏ ở hàng dưới */}
        <div className="destination-gallery">
            <div className="row mt-4">
                {randomDestinations.slice(3, 7).map(dest => (
                    <div key={dest.id} className="col-lg-3 col-md-6 col-sm-6 mb-4">
                        <div className="destination-item rounded-3 text-white">
                            <img src={dest.image} alt={dest.name} />
                            <div className="destination-info p-4 w-100">
                                <div className="destination-name">
                                    <p className="pera m-0 fs-2 fw-bold">{dest.name}</p>
                                    <div className="location d-flex gap-2">
                                        <i className="ri-map-pin-line"></i>
                                        <p className="name m-0">{dest.name}</p>
                                    </div>
                                </div>
                                <div className="arrow-btn">
                                    <i className="ri-arrow-right-line fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    </div>
</div>

                {/* Explore */}
                <div className="explore-section section">
                    <div className="container">
                        <div className="row text-center">
                            <div className="section-title">
                                <p>Explore The World</p>
                                <h2>Our Best Offer Package <br /> For you</h2>
                            </div>
                        </div>

                        <div className="row py-5 mt-5">
                            <div className="col-lg-6">
                                <div className="explore-tabs-wrap p-4 pb-2 rounded-3">
                                    {tabs.map((tab, index) => (
                                        <div
                                            key={index}
                                            className={`explore-tabs mb-4 ${activeIndex === index ? 'active' : ''}`}
                                            onClick={() => setActiveIndex(index)}
                                            style={{ cursor: 'pointer' }}
                                        >
                                            <h3>
                                                <img src={tab.img} className="me-3 img-fluid" alt="" />
                                                {tab.title}
                                            </h3>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div className="col-lg-6 explore-content text-white">
                                <h2 className="pb-3">{tabs[activeIndex].title}</h2>
                                <p className="mb-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, iusto. Error optio nesciunt qui tempora. Quia accusantium numquam velit exercitationem, sed quasi, accusamus illo earum saepe dolorum autem repudiandae neque molestias quaerat at, ullam assumenda dolore expedita praesentium. Delectus, magnam.</p>
                                <span><p className="mb-0 mb-2 ps-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus aspernatur magni tempore dolorum tempora itaque beatae quae velit perspiciatis quam!</p></span>
                                <span><p className="mb-0 mb-2 ps-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus aspernatur magni tempore dolorum tempora itaque beatae quae velit perspiciatis quam!</p></span>
                                <div className="explore-image">
                                    <img src={tabs[activeIndex].ExploreImg} className="img-fluid rounded-4" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Testimonial */}
                <div className="testimonial-container section">
                    <div className="container">
                        <div className="row text-center mb-5">
                            <div className="section-title">
                                <p>Testimonials</p>
                                <h2>What People Have Said <br /> About Our Service</h2>
                            </div>
                        </div>
                    </div>

                    <div className="container">
                        <div className="row align-items-center">
                            <div className="col-md-6">
                                <Swiper
                                    className="tst-swiper"
                                    loop={testimonials.length >= 3}
                                >
                                    {testimonials.length > 0 ? testimonials.map((testimonial, index) => (
                                        <SwiperSlide key={index}>
                                            <div className="tst-item">
                                                <div className="tst-user d-flex align-items-center gap-2">
                                                    <img src={testimonial.image || tst} alt="testimonials-image" width={50} height={50} className="img-fluid rounded-circle border-white" />
                                                    <p className="mb-0 fw-bold text-white fs-6">{testimonial.name}<span className="fw-normal"> {testimonial.role}</span></p>
                                                </div>
                                                <div className="tst-rating mt-3 mb-4">
                                                    {[...Array(5)].map((_, i) => (
                                                        <i key={i} className={`ri-star-fill ${i > 0 ? 'ps-1' : ''}`}></i>
                                                    ))}
                                                </div>
                                                <p className="fw-bold fs-5 mb-4">{testimonial.content}</p>
                                                <div className="tst-footer d-flex align-items-center justify-content-between">
                                                    <a href="#" className="text-white text-decoration-none fw-semibold text-uppercase fs-4 m-0">
                                                        Travel<span style={{ color: "rgb(242,111,85)" }}>Viet</span>
                                                    </a>
                                                    <p className="mb-0">{testimonial.date}</p>
                                                </div>
                                            </div>
                                        </SwiperSlide>
                                    )) : (
                                        <SwiperSlide>
                                            <div className="tst-item">
                                                <div className="tst-user d-flex align-items-center gap-2">
                                                    <img src={tst} alt="testimonials-image" width={50} height={50} className="img-fluid rounded-circle border-white" />
                                                    <p className="mb-0 fw-bold text-white fs-6">David Malan<span className="fw-normal"> Traveler</span></p>
                                                </div>
                                                <div className="tst-rating mt-3 mb-4">
                                                    <i className="ri-star-fill"></i>
                                                    <i className="ri-star-fill ps-1"></i>
                                                    <i className="ri-star-fill ps-1"></i>
                                                    <i className="ri-star-fill ps-1"></i>
                                                    <i className="ri-star-fill ps-1"></i>
                                                </div>
                                                <p className="fw-bold fs-5 mb-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum ipsam aliquid eum perferendis aliquam voluptate?</p>
                                                <div className="tst-footer d-flex align-items-center justify-content-between">
                                                    <a href="#" className="text-white text-decoration-none fw-semibold text-uppercase fs-4 m-0">
                                                        Travel<span style={{ color: "rgb(242,111,85)" }}>Viet</span>
                                                    </a>
                                                    <p className="mb-0">jan 20,2025</p>
                                                </div>
                                            </div>
                                        </SwiperSlide>
                                    )}
                                </Swiper>
                            </div>

                            <div className="col-md-6">
                                <div className="tst-banner rounded-5 overflow-hidden position-relative">
                                    <img src={tstbanner} alt="Testimonials-image" className="img-fuild" />
                                    <span className="bi bi-play-fill"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Brands */}
                <div className="brand-container section">
                    <div className="container">
                        <div className="row">
                            <Swiper
                                className="brand-swiper"
                                slidesPerView={4}
                                spaceBetween={30}
                                loop={brands.length >= 5}
                                autoplay={brands.length >= 5}
                                centeredSlides={true}
                            >
                                {brands.length > 0 ? brands.map((brand, index) => (
                                    <SwiperSlide key={index}>
                                        <div className="brand-image">
                                            <img src={brand.image || brand1} alt="brand-image" className="img-fluid" />
                                        </div>
                                    </SwiperSlide>
                                )) : (
                                    <>
                                        <SwiperSlide>
                                            <div className="brand-image">
                                                <img src={brand1} alt="brand-image" className="img-fluid" />
                                            </div>
                                        </SwiperSlide>
                                        <SwiperSlide>
                                            <div className="brand-image">
                                                <img src={brand2} alt="brand-image" className="img-fluid" />
                                            </div>
                                        </SwiperSlide>
                                        <SwiperSlide>
                                            <div className="brand-image">
                                                <img src={brand3} alt="brand-image" className="img-fluid" />
                                            </div>
                                        </SwiperSlide>
                                        <SwiperSlide>
                                            <div className="brand-image">
                                                <img src={brand4} alt="brand-image" className="img-fluid" />
                                            </div>
                                        </SwiperSlide>
                                        <SwiperSlide>
                                            <div className="brand-image">
                                                <img src={brand5} alt="brand-image" className="img-fluid" />
                                            </div>
                                        </SwiperSlide>
                                    </>
                                )}
                            </Swiper>
                        </div>
                    </div>
                </div>

                {/* Blog */}
                <div className="blog-container section">
                    <div className="container">
                        <div className="row text-center mb-5">
                            <div className="section-title">
                                <p>New & Article</p>
                                <h2>Latest News & Articles From the <br />Blog Posts</h2>
                            </div>
                        </div>
                    </div>

                    <div className="container">
                        <div className="row">
                            <Swiper
                                slidesPerView={3}
                                spaceBetween={24}
                                breakpoints={{
                                    1399: { slidesPerView: 3 },
                                    1199: { slidesPerView: 3 },
                                    991: { slidesPerView: 2 },
                                    767: { slidesPerView: 1.5 },
                                    0: { slidesPerView: 1 },
                                }}
                                className="blog-swiper"
                                loop={blogs.slice(0, 9).length >= 4}
                                navigation={true}
                                pagination={{ clickable: true }}
                            >
                                {blogs.slice(0, 9).map((blog) => (
                                    <SwiperSlide key={blog.id}>
                                        <div className="blog-card h-100">
                                            <div className="blog-img">
                                                <img
                                                    src={blog.thumbnail || news4}
                                                    alt={blog.title}
                                                    className="card-img-top rounded-3 w-100"
                                                    style={{ height: '240px', objectFit: 'cover' }}
                                                    onError={(e) => {
                                                        e.target.onerror = null;
                                                        e.target.src = news4;
                                                    }}
                                                />
                                            </div>
                                            <div className="blog-card-body d-flex flex-column">
                                                <h6 className="mb-2">{blog.category || 'Uncategorized'}</h6>
                                                <h5 className="card-title text-white mb-4">{blog.title}</h5>
                                                <div className="d-flex justify-content-between align-items-center mt-auto pt-3">
                                                    <div className="authors d-flex align-items-center gap-2">
                                                        <img
                                                            src={blog.author_avatar || user1}
                                                            alt={blog.author}
                                                            style={{ width: '32px', height: '32px', borderRadius: '50%', objectFit: 'cover' }}
                                                        />
                                                        <span className="author-name">{blog.author || 'Admin'}</span>
                                                    </div>
                                                    <span className="read-time">{blog.read_time || '5'} Min Read</span>
                                                </div>
                                            </div>
                                        </div>
                                    </SwiperSlide>
                                ))}
                            </Swiper>
                        </div>
                    </div>
                </div>

                {/* Price Section */}
                <div className="price-section section">
                    <div className="container">
                        <div className="section-title mb-5">
                            <div className="row text-center">
                                <p>Package Pricing Plan</p>
                                <h2>Simply Choose The Pricing Plan <br />That Fits You Best</h2>
                            </div>
                        </div>

                        <div className="row g-4">
                            {plans.map(plan => (
                                <div className="col-lg-4" key={plan.id}>
                                    <div className="pricing-card">
                                        <h5>
                                            {plan.name}
                                            {plan.is_popular && (
                                                <span className="popular-tag text-white ms-2">popular</span>
                                            )}
                                        </h5>
                                        <p className="mb-3">{plan.description}</p>

                                        {plan.price && (
                                            <div className="pricing-content d-flex align-items-center gap-3 border-top">
                                                <h2>{formatPrice(Number(plan.price))}</h2>
                                                <span>{plan.price_note}</span>
                                            </div>
                                        )}

                                        <ul className="list-unstyled mt-4">
                                            {(plan.features ?? []).map((feature, idx) => (
                                                <li className="mb-4" key={`feature-${idx}`}>
                                                    <i className="ri-check-line"></i> {feature}
                                                </li>
                                            ))}
                                            {(plan.disabled_features ?? []).map((feature, idx) => (
                                                <li className="mb-4 disabled-li" key={`disabled-${idx}`}>
                                                    <i className="ri-check-line"></i> {feature}
                                                </li>
                                            ))}
                                        </ul>

                                        <button
                                            className="btn text-white"
                                            onClick={() => handleTryNow(plan)}
                                        >
                                            {plan.button_text || 'Try Now'} <i className="ri-arrow-right-up-line"></i>
                                        </button>
                                        <p className="text-white mt-3">{plan.price_note || 'Liên hệ để biết thêm chi tiết'}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default Index;