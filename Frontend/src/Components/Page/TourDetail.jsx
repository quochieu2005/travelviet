import React, { useState, useContext } from "react";
import { CartContext } from "../../context/CartContext";

import tourData from "../../data/Tour.json";
import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay } from "swiper/modules"
import 'swiper/css'

import img1 from '../../../public/Image/image14.png'
import img2 from '../../../public/Image/image10.jpeg'
import img3 from '../../../public/Image/image15.jpeg'
import img4 from '../../../public/Image/image5.png'

import { useNavigate, useParams } from 'react-router-dom'

import { toast, ToastContainer } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'

function TourDetail() {

    const [showToast, setShowToast] = useState(false);
    const navigate = useNavigate();
    const { addTOCart, cartItems } = useContext(CartContext);

    const { slug } = useParams();

    const tour = tourData.Tours.find((t) => String(t.slug) === slug);

    const fallbackTour = {
        title: 'Dusitd2 Samyan Bangkok',
        slug: 'dusitd2-samyan-bangkok',
        location: 'Bangkok, ThaiLand',
        duration: '3 days 2 night',
        "destination id": 2,
        "category id": 5,
        "availability": 12,
        "max_people": 25,
        "views": 1300,
        review: 95,
        rating: 4.8,
        "price adult": 4500000,
        "price child": 2200000,
        "price discount percent": 15,
        "price child discount percent": 5,
        "discount price": 3825000,
        "discount price child": 2090000,
        "departure location": "TP.HCM",
        image: '/Image/image2.png'
    }

    const selectedTour = tour || fallbackTour;

    const addToCart = (tour) => {
        const alreadyAdded = cartItems.find((item) => item.id === tour.id);

        if (!alreadyAdded) {
            addTOCart({ ...tour, type: 'tour' });

            toast.success('Tour Added to Cart!', {
                position: 'top-right',
                autoClose: 1500,
                theme: 'dark',
            });

            setTimeout(() => {
                navigate('/cart');
            }, 1600);
        } else {
            toast.info('Tour already added to cart!', {
                position: 'top-right',
                autoClose: 1500,
                theme: 'dark',
            });
        }
    }

    return (
        <>
            <ToastContainer />
            <div className="tour-detail-page">
                <div className="tour-slider">
                    <Swiper
                        modules={[Autoplay]}
                        slidesPerView={1.8}
                        spaceBetween={30}
                        centeredSlides={true}
                        loop={true}
                        autoplay={{
                            delay: 2500,
                            disableOnInteraction: false
                        }}
                        className="tourSwiper"
                    >
                        <SwiperSlide>
                            <img src={img1} className="tour-slide-img" alt="slide 1" />
                        </SwiperSlide>

                        <SwiperSlide>
                            <img src={img2} className="tour-slide-img active-slide" alt="slide 2" />
                        </SwiperSlide>

                        <SwiperSlide>
                            <img src={img3} className="tour-slide-img" alt="slide 3" />
                        </SwiperSlide>

                        <SwiperSlide>
                            <img src={img4} className="tour-slide-img active-slide" alt="slide 4" />
                        </SwiperSlide>
                    </Swiper>
                </div>
            </div>

            {/* Tour Details Section */}
            <div className="about-detail-section pt-5">
                <div className="container">
                    <div className="row">
                        <div className="col-md-8">
                            <h4 className="tour-title">{selectedTour.title}</h4>

                            <div className="d-flex gap-3 align-items-center mb-3">
                                <span><i className="ri-map-pin-line"></i>{selectedTour.location}</span>
                                <span><i className="ri-calendar-2-line"></i>{selectedTour.duration}</span>
                                <span><i className="ri-user-3-line"></i>{selectedTour.max_people} người</span>
                            </div>

                            <div className="d-flex justify-content-between align-items-center mb-4">
                                {/* Giá người lớn */}
                                <div>
                                    <strong>From <span className="fs-2"></span>
                                        {selectedTour['price discount percent'] > 0 ? (
                                            <>
                                                <span className="text-decoration-line-through text-muted me-2">
                                                    {selectedTour['price adult'].toLocaleString('vi-VN')}₫
                                                </span>
                                                <span className="text-danger fw-bold">
                                                    {selectedTour['discount price'].toLocaleString('vi-VN')}₫
                                                </span>
                                                <span className="badge bg-danger rounded-pill ms-2">
                                                    -{selectedTour['price discount percent']}%
                                                </span>
                                            </>
                                        ) : (
                                            <span>{selectedTour['price adult'].toLocaleString('vi-VN')}₫</span>
                                        )}
                                    </strong>
                                </div>

                                {/* Giá trẻ em */}
                                <div>
                                    <strong>From <span className="fs-2"></span>
                                        {selectedTour['price child discount percent'] > 0 ? (
                                            <>
                                                <span className="text-decoration-line-through text-muted me-2">
                                                    {selectedTour['price child'].toLocaleString('vi-VN')}₫
                                                </span>
                                                <span className="text-danger fw-bold">
                                                    {selectedTour['discount price child'].toLocaleString('vi-VN')}₫
                                                </span>
                                                <span className="badge bg-danger rounded-pill ms-2">
                                                    -{selectedTour['price child discount percent']}%
                                                </span>
                                            </>
                                        ) : (
                                            <span>{selectedTour['price child'].toLocaleString('vi-VN')}₫</span>
                                        )}
                                    </strong>
                                </div>

                                <div className="text-warning">
                                    <i className="ri-star-fill"></i> {selectedTour.rating} ({selectedTour.review} reviews)
                                </div>
                            </div>

                            <section className="mb-4">
                                <h5 className="mb-2">About</h5>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and mor</p>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and mor</p>
                            </section>

                            <div className="row border rounded p-2">
                                <div className="col-md-6">
                                    <h5 className="mb-2">Included</h5>
                                    <ul className="list-unstyle-detail">
                                        <li> Welcome Breakfast </li>
                                        <li> All Entry Tickets </li>
                                        <li> Lunch & Dinner </li>
                                        <li> Evening Snacks </li>
                                        <li> First Aid </li>
                                    </ul>
                                </div>

                                <div className="col-md-6">
                                    <h5 className="mb-2">Excluded</h5>
                                    <ul className="list-unstyle-detail">
                                        <li> Personal Expenses </li>
                                        <li> Unmentioned Activities </li>
                                        <li> Additional Service </li>
                                    </ul>
                                </div>
                            </div>

                            {/* Tour Plan */}
                            <section className="mt-4">
                                <h5 className="mb-3">Tour Plan</h5>
                                <div className="accordion" id="tourPlanAccordion">
                                    {["Day 1", "Day 2", "Day 3"].map((day, index) => (
                                        <div className="accordion-item" key={index}>
                                            <h2 className="accordion-header" id="{`heading${index}`}">
                                                <button
                                                    className={`accordion-button ${index > 0 ? 'collapsed' : ''}`}
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target={`#collapse${index}`}
                                                    aria-expanded={index === 0}
                                                    aria-controls={`collapse${index}`}
                                                >
                                                    {day} - {selectedTour.location}
                                                </button>
                                            </h2>

                                            <div
                                                id={`collapse${index}`}
                                                className={`accordion-collapse collapse ${index === 0 ? 'show' : ''}`}
                                                aria-labelledby={`heading${index}`}
                                                data-bs-parent="#tourPlanAccordion"
                                            >
                                                <div className="accordion-body bg-dark text-white">
                                                    but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                                                    <ul className="list-unstyle-detail">
                                                        <li> Personal Expenses </li>
                                                        <li> Unmentioned Activities </li>
                                                        <li> Additional Service </li>
                                                    </ul>

                                                    {day}.
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </section>

                            <section className="mt-4">
                                <h5 className="mb-2">Policy</h5>
                                <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

                                <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

                                <ol className="list-numbered">
                                    <li>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</li>
                                    <li>If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</li>
                                    <li>All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.</li>
                                </ol>
                            </section>

                        </div>

                        <div className="col-md-4">
                            <div className="p-4 rounded-4 shadow-lg booking-widget bg-dark text-white">
                                <h6 className="text-muted mb-3">From</h6>
                                <div className="mb-3">
                                    <div className="text-muted small">Người lớn</div>
                                    <h3 className="fw-bold text-light mb-2">
                                        {selectedTour['price discount percent'] > 0 ? (
                                            <>
                                                <span className="text-decoration-line-through text-muted me-2 fs-6">
                                                    {selectedTour['price adult'].toLocaleString('vi-VN')}₫
                                                </span>
                                                <span className="text-warning">
                                                    {selectedTour['discount price'].toLocaleString('vi-VN')}₫
                                                </span>
                                                <span className="badge bg-danger rounded-pill ms-2">
                                                    -{selectedTour['price discount percent']}%
                                                </span>
                                            </>
                                        ) : (
                                            <span className="text-warning">
                                                {selectedTour['price adult'].toLocaleString('vi-VN')}₫
                                            </span>
                                        )}
                                    </h3>
                                </div>

                                <div>
                                    <div className="text-muted small">Trẻ em</div>
                                    <h3 className="fw-bold text-light">
                                        {selectedTour['price child discount percent'] > 0 ? (
                                            <>
                                                <span className="text-decoration-line-through text-muted me-2 fs-6">
                                                    {selectedTour['price child'].toLocaleString('vi-VN')}₫
                                                </span>
                                                <span className="text-warning">
                                                    {selectedTour['discount price child'].toLocaleString('vi-VN')}₫
                                                </span>
                                                <span className="badge bg-danger rounded-pill ms-2">
                                                    -{selectedTour['price child discount percent']}%
                                                </span>
                                            </>
                                        ) : (
                                            <span className="text-warning">
                                                {selectedTour['price child'].toLocaleString('vi-VN')}₫
                                            </span>
                                        )}
                                    </h3>
                                </div>

                                <div>
                                    <div className="mb-3">
                                        <label className="text-light p-2">Guests</label>
                                        <select className="form-select bg-dark border-secondary text-white">
                                            <option>1 guest</option>
                                            <option>2 guests</option>
                                            <option>3 guests</option>
                                        </select>
                                    </div>

                                    <button
                                        type="button"
                                        className="btn btn-secondary w-100 mt-3 d-flex align-items-center justify-content-center gap-2"
                                        onClick={() => addToCart(selectedTour)}
                                    >
                                        <i className="ri-shopping-cart-line fs-5"></i>
                                        <span>Book Now</span>
                                    </button>
                                </div>

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
    )
}

export default TourDetail
