import React, { useState, useEffect } from 'react'

import aboutbanner from "../../assets/about-banner-three.png"

import brand1 from "../../assets/brand-1.jpeg"
import brand2 from "../../assets/brand-2.jpeg"
import brand3 from "../../assets/brand-3.jpeg"
import brand4 from "../../assets/brand-4.png"
import brand5 from "../../assets/brand-5.png"

import { Swiper, SwiperSlide } from 'swiper/react'
import 'swiper/css'
import 'swiper/css/autoplay'

import { getPricingPlans, submitPricingInquiry } from '../../services/aboutApi'

function About() {
    const [plans, setPlans]               = useState([])
    const [selectedPlan, setSelectedPlan] = useState(null)
    const [showModal, setShowModal]       = useState(false)
    const [form, setForm]                 = useState({ name: '', email: '', phone: '', message: '' })
    const [loading, setLoading]           = useState(false)
    const [toast, setToast]               = useState(null)

    useEffect(() => {
        getPricingPlans()
            .then(data => { if (data.success) setPlans(data.data) })
            .catch(() => {})
    }, [])

    const handleTryNow = (plan) => {
        setSelectedPlan(plan)
        setForm({ name: '', email: '', phone: '', message: '' })
        setShowModal(true)
    }

    const handleCloseModal = () => {
        setShowModal(false)
        setSelectedPlan(null)
    }

    const handleSubmit = async (e) => {
        e.preventDefault()
        setLoading(true)
        try {
            const data = await submitPricingInquiry({
                pricing_plan_id: selectedPlan.id,
                ...form,
            })
            if (data.success) {
                setShowModal(false)
                showToast('success', data.message)
            } else {
                showToast('error', 'Có lỗi xảy ra, vui lòng thử lại.')
            }
        } catch {
            showToast('error', 'Không thể kết nối server.')
        } finally {
            setLoading(false)
        }
    }

    const showToast = (type, message) => {
        setToast({ type, message })
        setTimeout(() => setToast(null), 4000)
    }

    const formatPrice = (price) =>
        new Intl.NumberFormat('vi-VN').format(price) + 'đ'

    return (
        <>
            {/* Toast */}
            {toast && (
                <div style={{
                    position: 'fixed', top: '20px', right: '20px', zIndex: 9999,
                    padding: '14px 20px', borderRadius: '8px', color: '#fff',
                    background: toast.type === 'success' ? '#28a745' : '#dc3545',
                    boxShadow: '0 4px 12px rgba(0,0,0,0.2)',
                }}>
                    <i className={`ri-${toast.type === 'success' ? 'checkbox-circle' : 'error-warning'}-line me-2`}></i>
                    {toast.message}
                </div>
            )}

            {/* Modal đăng ký gói */}
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

                        <form onSubmit={handleSubmit}>
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
                                type="submit" className="btn w-100" disabled={loading}
                                style={{ background: 'var(--primary-color)', color: '#fff' }}
                            >
                                {loading
                                    ? <><i className="ri-loader-4-line me-2"></i>Đang gửi...</>
                                    : <><i className="ri-send-plane-line me-2"></i>Gửi đăng ký</>
                                }
                            </button>
                        </form>
                    </div>
                </div>
            )}

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

            {/* Price */}
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
                                            <h2>{formatPrice(plan.price)}</h2>
                                            <span>{plan.price_note}</span>
                                        </div>
                                    )}

                                    <ul className="list-unstyled mt-4">
                                        {(plan.features ?? []).map((f, i) => (
                                            <li className="mb-4" key={`f-${i}`}>
                                                <i className="ri-check-line"></i> {f}
                                            </li>
                                        ))}
                                        {(plan.disabled_features ?? []).map((f, i) => (
                                            <li className="mb-4 disabled-li" key={`d-${i}`}>
                                                <i className="ri-check-line"></i> {f}
                                            </li>
                                        ))}
                                    </ul>

                                    <button
                                        className="btn text-white"
                                        onClick={() => handleTryNow(plan)}
                                    >
                                        {plan.button_text} <i className="ri-arrow-right-up-line"></i>
                                    </button>
                                    <p className="text-white mt-3">{plan.price_note}</p>
                                </div>
                            </div>
                        ))}
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
                            loop={true}
                            autoplay={true}
                            centeredSlides={true}
                        >
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
                        </Swiper>
                    </div>
                </div>
            </div>

        </>
    )
}

export default About