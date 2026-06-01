import { useEffect, useState, useContext } from 'react'

import { CartContext } from '../../context/CartContext'
import tourData from '../../data/Tour.json'
import { Link } from 'react-router-dom'
import { toast, ToastContainer } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'

function Tours() {

    const [tours, setTours] = useState([]);
    const [visibleCount, setVisibleCount] = useState(6);
    const { cartItems, addTOCart } = useContext(CartContext);
    const imageModules = import.meta.glob('../../assets/*.{png,jpg,jpeg}', { eager: true });


    const getImage = (imagePath) => {
        const fileName = imagePath.split('/').pop();
        return imageModules[`../../assets/${fileName}`]?.default || '';
    };

    useEffect(() => {
        setTours(tourData.Tours);
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
                                        <option>USA</option>
                                        <option>Turkey</option>
                                        <option>Switerland</option>
                                        <option>ThaiLand</option>
                                        <option>VietNam</option>
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

                                                {tour['price discount percent'] > 0 && (
                                                    <div className="small mt-1 ms-1">
                                                        <span className="text-muted text-decoration-line-through">
                                                            {tour['price adult'].toLocaleString('vi-VN')}₫
                                                        </span>
                                                    </div>
                                                )}

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

                        {visibleCount < tours.length && (
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
