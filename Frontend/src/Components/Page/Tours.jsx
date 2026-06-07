import { useEffect, useState, useContext } from 'react'
import { CartContext } from '../../context/CartContext'
import { getTours } from '../../services/tourApi'
import { Link } from 'react-router-dom'
import { toast, ToastContainer } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'

function Tours() {
    const [tours, setTours] = useState([]);
    const [destinationsMap, setDestinationsMap] = useState({});
    const [visibleCount, setVisibleCount] = useState(6);
    const [loading, setLoading] = useState(true);
    const { cartItems, addTOCart } = useContext(CartContext);

    useEffect(() => {
        getTours()
            .then((res) => {
                setTours(res.data.Tours || []);
                setDestinationsMap(res.data.destinations || {});
            })
            .catch((err) => {
                console.error('Lỗi tải tour:', err);
                toast.error('Không thể tải danh sách tour!');
            })
            .finally(() => setLoading(false));
    }, []);

    const handleBookNow = (tour) => {
        const alreadyInCart = cartItems.find((item) => item.id === tour.id);
        if (alreadyInCart) {
            toast.warning("Tour đã có trong giỏ hàng!");
        } else {
            addTOCart({ ...tour, quantity: 1, type: 'tour' });
            toast.success(`Đã thêm ${tour.title} vào giỏ!`);
        }
    }

    const loadMore = () => {
        setVisibleCount((prev) => prev + 6);
    }

    const getDestinationName = (destinationId) => {
        return destinationsMap[destinationId] || 'Đang cập nhật';
    };

    return (
        <>
            <div className="main-wrapper">
                <ToastContainer position='top-right' autoClose={2500} theme='dark' />

                <div className="container">
                    <div className="row">
                        <div className="col-lg-3 mb-4">
                            <div className="filter-sidebar shadow-sm">
                                <h5 className="fw-bold mb-4 d-flex align-items-center">
                                    <i className="ri-filter-3-fill me-2 text-secondary"></i>
                                    Advanced Filter
                                </h5>

                                <fieldset className='filter-section'>
                                    <legend><i className="ri-map-pin-line me-2"></i>Destination</legend>
                                    <select className='form-select'>
                                        <option value="">Select Destination</option>
                                        {Object.entries(destinationsMap).map(([id, name]) => (
                                            <option key={id} value={id}>{name}</option>
                                        ))}
                                    </select>
                                </fieldset>

                                <fieldset className='filter-section'>
                                    <legend><i className="ri-flight-takeoff-line me-2"></i>Tour Type</legend>
                                    <select className='form-select'>
                                        <option value="">Select Type</option>
                                        <option>Adventure</option>
                                        <option>Relaxation</option>
                                        <option>Cultural</option>
                                    </select>
                                </fieldset>

                                <fieldset className='filter-section'>
                                    <legend><i className="ri-calendar-event-line me-2"></i>Date Form</legend>
                                    <input type="date" className='form-control' />
                                </fieldset>

                                <fieldset className='filter-section'>
                                    <legend><i className="ri-user-smile-line me-2"></i>Guests</legend>
                                    <input type="number" className='form-control' placeholder='number of Guest' min={1} />
                                </fieldset>

                                <fieldset className='filter-section'>
                                    <legend><i className="ri-star-smile-line me-2"></i>Traveler Rating</legend>
                                    <div className="d-flex flex-wrap gap-2">
                                        {[1, 2, 3, 4, 5].map((star) => (
                                            <span key={star} className='rating-badge'>
                                                <i className="ri-star-fill text-warning me-1"></i>
                                                {star}
                                            </span>
                                        ))}
                                    </div>
                                </fieldset>

                                <fieldset className='filter-section'>
                                    <legend><i className="ri-price-tag-3-line me-2"></i>Special Offers</legend>
                                    <div className="form-check">
                                        <input type="checkbox" id='likely' className="form-check-input" />
                                        <label htmlFor="likely" className='form-check-label'>Likely to Sell Out</label>
                                    </div>
                                    <div className="form-check">
                                        <input type="checkbox" id='discount' className="form-check-input" />
                                        <label htmlFor="discount" className='form-check-label'>Winter Discount</label>
                                    </div>
                                </fieldset>

                                <fieldset className='filter-section'>
                                    <legend><i className="ri-translate-2 me-2"></i>Languages</legend>
                                    {["English", "Spanish", "French", "Bangla"].map((lang, i) => (
                                        <div className="form-check" key={i}>
                                            <input type="checkbox" className="form-check-input" id={lang} />
                                            <label htmlFor={lang} className='form-check-label'>{lang}</label>
                                        </div>
                                    ))}
                                </fieldset>
                            </div>
                        </div>

                        <div className="col-lg-9">
                            <div className="row">
                                {tours.slice(0, visibleCount).map((tour) => (
                                    <div className="col-md-6 col-lg-4 mb-4" key={tour.id}>
                                        <div className="card h-100 tour-card shadow-sm position-relative">
                                            <div className="position-relative">
                                                <Link to={`/TourDetail/${tour.slug}`}>
                                                    <img
                                                        src={tour.thumbnail}
                                                        className="card-img-top rounded"
                                                        alt={tour.title}
                                                        onError={(e) => { e.target.src = '/placeholder.jpg' }}
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
                                                    <i className="ri-map-pin-line me-1"></i>
                                                    {getDestinationName(tour.destination_id)}
                                                </p>
                                                <div className="d-flex flex-wrap justify-content-between small mb-3 p-2 rounded" style={{ backgroundColor: 'rgba(248,250,252,.08)' }}>
                                                    <div className="me-3"><i className="ri-time-line me-1"></i>{tour.duration_days} ngày</div>
                                                    <div><i className="ri-user-line me-1"></i>{tour.max_people} người</div>
                                                </div>

                                                {/* Giá discount */}
                                                <div className="d-flex mt-2 align-items-center justify-content-between">
                                                    <div className="ms-1" style={{ color: '#8f94a3' }}>
                                                        From
                                                        <span className="text-warning fw-bold ms-1 fs-5">
                                                            {Number(tour.discount_price).toLocaleString('vi-VN')}₫
                                                        </span>
                                                    </div>
                                                    {tour.price_discount_percent > 0 && (
                                                        <span className="badge bg-danger rounded-pill me-1">
                                                            {tour.price_discount_percent >= 100
                                                                ? `-${Number(tour.price_discount_percent).toLocaleString('vi-VN')}₫`
                                                                : `-${tour.price_discount_percent}%`
                                                            }
                                                        </span>
                                                    )}
                                                </div>

                                                {tour.price_discount_percent > 0 && (
                                                    <div className="small mt-1 ms-1">
                                                        <span className="text-muted text-decoration-line-through">
                                                            {Number(tour.price_adult).toLocaleString('vi-VN')}₫
                                                        </span>
                                                    </div>
                                                )}

                                                {/* Giá trẻ em */}
                                                <div className="small mt-2" style={{ color: '#8f94a3' }}>
                                                    <i className="ri-user-star-line me-1"></i>
                                                    Trẻ em: {Number(tour.discount_price_child).toLocaleString('vi-VN')}₫
                                                    {tour.price_child_discount_percent > 0 && (
                                                        <>
                                                            <span className="text-muted text-decoration-line-through ms-1 small">
                                                                {Number(tour.price_child).toLocaleString('vi-VN')}₫
                                                            </span>
                                                            <span className="ms-1 text-danger small">
                                                                {tour.price_child_discount_percent >= 100
                                                                    ? `(-${Number(tour.price_child_discount_percent).toLocaleString('vi-VN')}₫)`
                                                                    : `(-${tour.price_child_discount_percent}%)`
                                                                }
                                                            </span>
                                                        </>
                                                    )}
                                                </div>

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
                                    </div>
                                ))}
                            </div>
                        </div>

                        {!loading && visibleCount < tours.length && (
                            <div className="text-center mb-4">
                                <button className="btn btn-primary px-4 py-2" onClick={loadMore}>
                                    Load More
                                </button>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </>
    )
}

export default Tours