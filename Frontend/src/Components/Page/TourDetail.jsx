import React, { useState, useEffect, useContext } from "react";
import { CartContext } from "../../context/CartContext";
import { getTourBySlug } from "../../services/tourApi";
import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay } from "swiper/modules";
import "swiper/css";
import { useNavigate, useParams } from "react-router-dom";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

function TourDetail() {
    const { slug } = useParams();
    const navigate = useNavigate();
    const { addTOCart, cartItems } = useContext(CartContext);

    const [tour, setTour] = useState(null);
    const [images, setImages] = useState([]);
    const [loading, setLoading] = useState(true);
    const [quantityAdult, setQuantityAdult] = useState(1);
    const [quantityChild, setQuantityChild] = useState(0);

    useEffect(() => {
        getTourBySlug(slug)
            .then((res) => {
                if (res.data.success) {
                    setTour(res.data.tour);
                    setImages(res.data.images || []);
                }
            })
            .catch(() => {
                toast.error("Không thể tải thông tin tour!");
            })
            .finally(() => setLoading(false));
    }, [slug]);

    const calcDiscount = (originalPrice, discountValue) => {
        if (!discountValue || discountValue <= 0 || !originalPrice) {
            return { finalPrice: originalPrice || 0, percent: 0 };
        }
        if (discountValue >= 100) {
            return {
                finalPrice: Math.max(0, originalPrice - discountValue),
                percent: Math.round((discountValue / originalPrice) * 100),
            };
        }
        return {
            finalPrice: Math.round(originalPrice * (1 - discountValue / 100)),
            percent: discountValue,
        };
    };

    const formatVND = (num) =>
        Number(num).toLocaleString("vi-VN") + "₫";

    const parseItinerary = (data) => {
        if (!data) return [];
        if (Array.isArray(data)) return data;

        try {
            const parsed = JSON.parse(data);
            if (Array.isArray(parsed)) return parsed;
        } catch (e) { }

        if (typeof data === 'string' && data.trim()) {
            const days = [];
            const lines = data.split(/\r?\n/);
            let currentDay = null;
            let currentDescription = [];

            for (let i = 0; i < lines.length; i++) {
                const line = lines[i].trim();
                if (!line) continue;

                const dayMatch = line.match(/^(NGÀY|Ngày|DAY|Day)\s+(\d+)[:\s]*(.*)$/i);

                if (dayMatch) {
                    if (currentDay) {
                        days.push({
                            title: currentDay,
                            description: currentDescription.join('\n')
                        });
                    }
                    currentDay = `Ngày ${dayMatch[2]}: ${dayMatch[3]}`;
                    currentDescription = [];
                } else if (currentDay) {
                    currentDescription.push(line);
                }
            }

            if (currentDay) {
                days.push({
                    title: currentDay,
                    description: currentDescription.join('\n')
                });
            }

            return days;
        }

        return [];
    };

    // Hàm lấy dữ liệu included/excluded an toàn
    const getServiceItems = (data) => {
        if (!data) return [];
        if (Array.isArray(data)) return data;
        if (typeof data === 'string') {
            try {
                const parsed = JSON.parse(data);
                return Array.isArray(parsed) ? parsed : [data];
            } catch (e) {
                return [data];
            }
        }
        return [];
    };

    const handleAddToCart = () => {
        const alreadyAdded = cartItems.find((item) => item.id === tour.id && item.type === 'tour');
        if (alreadyAdded) {
            toast.info("Tour đã có trong giỏ hàng!", { position: "top-right", autoClose: 1500, theme: "dark" });
            return;
        }

        const priceAdult = parseFloat(tour.price_adult) || 0;
        const priceChild = parseFloat(tour.price_child) || 0;
        const adultDiscount = calcDiscount(priceAdult, parseFloat(tour.price_discount_percent));
        const childDiscount = calcDiscount(priceChild, parseFloat(tour.price_child_discount_percent));

        addTOCart({
            ...tour,
            type: "tour",
            quantityAdult,
            quantityChild,
            discount_price: adultDiscount.finalPrice,
            discount_price_child: childDiscount.finalPrice,
        });

        toast.success("Tour đã thêm vào giỏ!", { position: "top-right", autoClose: 1500, theme: "dark" });
        setTimeout(() => navigate("/cart"), 1600);
    };

    if (loading) {
        return (
            <div className="d-flex justify-content-center align-items-center" style={{ height: '100vh' }}>
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        );
    }

    if (!tour) {
        return (
            <div className="container text-center mt-5">
                <h3>Không tìm thấy tour!</h3>
                <button className="btn btn-primary" onClick={() => navigate('/tours')}>
                    Quay lại danh sách tour
                </button>
            </div>
        );
    }

    const priceAdult = parseFloat(tour.price_adult) || 0;
    const priceChild = parseFloat(tour.price_child) || 0;
    const adultDiscount = calcDiscount(priceAdult, parseFloat(tour.price_discount_percent));
    const childDiscount = calcDiscount(priceChild, parseFloat(tour.price_child_discount_percent));

    const itineraryItems = parseItinerary(tour.itinerary);

    // Lấy dữ liệu included/excluded an toàn
    const includedItems = getServiceItems(tour.included_services || tour.included);
    const excludedItems = getServiceItems(tour.excluded_services || tour.excluded);

    return (
        <>
            <ToastContainer />
            <div className="tour-detail-page">
                <div className="tour-slider">
                    <Swiper
                        modules={[Autoplay]}
                        slidesPerView={images.length >= 2 ? 1.8 : 1}
                        spaceBetween={30}
                        centeredSlides={true}
                        loop={images.length >= 2}
                        autoplay={{ delay: 2500, disableOnInteraction: false }}
                        className="tourSwiper"
                    >
                        {images.length > 0 ? (
                            images.map((img, idx) => (
                                <SwiperSlide key={idx}>
                                    <img
                                        src={img}
                                        className="tour-slide-img"
                                        alt={`slide-${idx + 1}`}
                                        onError={(e) => { e.target.src = "/placeholder.jpg"; }}
                                    />
                                </SwiperSlide>
                            ))
                        ) : (
                            <SwiperSlide>
                                <img src="/placeholder.jpg" className="tour-slide-img" alt="placeholder" />
                            </SwiperSlide>
                        )}
                    </Swiper>

                </div>
            </div>

            <div className="about-detail-section pt-5">
                <div className="container">
                    <div className="row">
                        <div className="col-md-8">
                            <h4 className="tour-title">{tour.title}</h4>

                            <div className="d-flex gap-3 align-items-center mb-3">
                                <span><i className="ri-map-pin-line"></i> {tour.location || tour.departure_location}</span>
                                <span><i className="ri-calendar-2-line"></i> {tour.duration_days} ngày</span>
                                <span><i className="ri-user-3-line"></i> {tour.max_people} người</span>
                            </div>

                            <div className="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <strong>Người lớn: </strong>
                                    {adultDiscount.percent > 0 ? (
                                        <>
                                            <span className="text-decoration-line-through text-muted me-2">
                                                {formatVND(priceAdult)}
                                            </span>
                                            <span className="text-danger fw-bold">
                                                {formatVND(adultDiscount.finalPrice)}
                                            </span>
                                            <span className="badge bg-danger rounded-pill ms-2">
                                                -{adultDiscount.percent}%
                                            </span>
                                        </>
                                    ) : (
                                        <span>{formatVND(priceAdult)}</span>
                                    )}
                                </div>

                                <div>
                                    <strong>Trẻ em: </strong>
                                    {childDiscount.percent > 0 ? (
                                        <>
                                            <span className="text-decoration-line-through text-muted me-2">
                                                {formatVND(priceChild)}
                                            </span>
                                            <span className="text-danger fw-bold">
                                                {formatVND(childDiscount.finalPrice)}
                                            </span>
                                            <span className="badge bg-danger rounded-pill ms-2">
                                                -{childDiscount.percent}%
                                            </span>
                                        </>
                                    ) : (
                                        <span>{formatVND(priceChild)}</span>
                                    )}
                                </div>

                                <div className="text-warning">
                                    <i className="ri-star-fill"></i> {tour.rating} ({tour.review} reviews)
                                </div>
                            </div>

                            <section className="mb-4">
                                <h5 className="mb-2">{tour.short_description}</h5>
                                <p>{tour.description || "Thông tin đang được cập nhật."}</p>
                            </section>

                            <div className="row border rounded p-2">
                                <div className="col-md-6">
                                    <h5 className="mb-2">Included</h5>
                                    <ul className="list-unstyle-detail">
                                        {includedItems.length > 0 ? (
                                            includedItems.map((item, i) => <li key={i}>{item}</li>)
                                        ) : (
                                            <li>Không có thông tin</li>
                                        )}
                                    </ul>
                                </div>
                                <div className="col-md-6">
                                    <h5 className="mb-2">Excluded</h5>
                                    <ul className="list-unstyle-detail">
                                        {excludedItems.length > 0 ? (
                                            excludedItems.map((item, i) => <li key={i}>{item}</li>)
                                        ) : (
                                            <li>Không có thông tin</li>
                                        )}
                                    </ul>
                                </div>
                            </div>

                            <section className="mt-4">
                                <h5 className="mb-3">Tour Plan</h5>
                                <div className="accordion" id="tourPlanAccordion">
                                    {itineraryItems.length > 0 ? (
                                        itineraryItems.map((day, index) => (
                                            <div className="accordion-item" key={index}>
                                                <h2 className="accordion-header">
                                                    <button
                                                        className={`accordion-button ${index > 0 ? "collapsed" : ""}`}
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target={`#collapse${index}`}
                                                        aria-expanded={index === 0}
                                                    >
                                                        {day.title}
                                                    </button>
                                                </h2>
                                                <div
                                                    id={`collapse${index}`}
                                                    className={`accordion-collapse collapse ${index === 0 ? "show" : ""}`}
                                                    data-bs-parent="#tourPlanAccordion"
                                                >
                                                    <div className="accordion-body bg-dark text-white">
                                                        {day.description ? (
                                                            day.description.split('\n').map((line, i) => (
                                                                line.trim() && <div key={i}>{line}</div>
                                                            ))
                                                        ) : (
                                                            <div>Thông tin đang được cập nhật.</div>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        ))
                                    ) : (
                                        <div className="accordion-body bg-dark text-white">
                                            {tour.itinerary ? (
                                                tour.itinerary.split('\n').map((line, i) => (
                                                    line.trim() && <div key={i}>{line}</div>
                                                ))
                                            ) : (
                                                <div>Thông tin đang được cập nhật.</div>
                                            )}
                                        </div>
                                    )}
                                </div>
                            </section>

                            <section className="mt-4">
                                <h5 className="mb-2">Policy</h5>
                                <p>{tour.policy || "Vui lòng liên hệ để biết thêm chính sách."}</p>
                            </section>
                        </div>

                        <div className="col-md-4">
                            <div className="p-4 rounded-4 shadow-lg booking-widget bg-dark text-white">
                                <h6 className="text-muted mb-3">From</h6>

                                <div className="mb-3">
                                    <div className="text-muted small">Người lớn</div>
                                    <h5 className="fw-bold text-light mb-2">
                                        {adultDiscount.percent > 0 ? (
                                            <>
                                                <span className="text-decoration-line-through text-muted me-2 fs-6">
                                                    {formatVND(priceAdult)}
                                                </span>
                                                <span className="text-warning">
                                                    {formatVND(adultDiscount.finalPrice)}
                                                </span>
                                                <span className="badge bg-danger rounded-pill ms-2">
                                                    -{adultDiscount.percent}%
                                                </span>
                                            </>
                                        ) : (
                                            <span className="text-warning">{formatVND(priceAdult)}</span>
                                        )}
                                    </h5>
                                </div>

                                <div className="mb-3">
                                    <div className="text-muted small">Trẻ em</div>
                                    <h5 className="fw-bold text-light">
                                        {childDiscount.percent > 0 ? (
                                            <>
                                                <span className="text-decoration-line-through text-muted me-2 fs-6">
                                                    {formatVND(priceChild)}
                                                </span>
                                                <span className="text-warning">
                                                    {formatVND(childDiscount.finalPrice)}
                                                </span>
                                                <span className="badge bg-danger rounded-pill ms-2">
                                                    -{childDiscount.percent}%
                                                </span>
                                            </>
                                        ) : (
                                            <span className="text-warning">{formatVND(priceChild)}</span>
                                        )}
                                    </h5>
                                </div>

                                <button
                                    type="button"
                                    className="btn btn-secondary w-100 mt-2 d-flex align-items-center justify-content-center gap-2"
                                    onClick={handleAddToCart}
                                >
                                    <i className="ri-shopping-cart-line fs-5"></i>
                                    <span>Book Now</span>
                                </button>

                                <div className="small mt-4 text-muted border-top pt-3">
                                    <i className="ri-check-double-line text-success me-2"></i>
                                    Free Cancellation - Up To 24h Advance
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

export default TourDetail;