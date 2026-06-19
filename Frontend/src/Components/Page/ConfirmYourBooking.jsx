import { useEffect, useState } from 'react'
import { getProfile } from '../../services/authService'
import PaymentPage from './Payment'

function CheckoutPage({ cartItems = [], bookingDate, destination = "" }) {

    const [checkInDate, setCheckInDate] = useState(bookingDate || "");
    const [checkOutDate, setCheckOutDate] = useState("");
    const [showCheckout, setShowCheckout] = useState(false);
    const [profileLoaded, setProfileLoaded] = useState(false);

    const [form, setForm] = useState({
        fullName: "",
        email: "",
        phone: "",
        country: "",
        city: "",
        notes: "",
    });

    useEffect(() => {
        getProfile()
            .then(res => {
                const user = res.data.user || res.data;
                setForm(prev => ({
                    ...prev,
                    fullName: user.full_name   || prev.fullName,
                    email:    user.email       || prev.email,
                    phone:    user.phone       || prev.phone,
                    country:  user.nationality || prev.country,
                    city:     user.address     || prev.city,
                }));
                setProfileLoaded(true);
            })
            .catch(() => {});
    }, []);

    const handleChange = (e) => {
        setForm(prev => ({ ...prev, [e.target.name]: e.target.value }));
    };

    // ✅ Helper phân loại
    const isTourItem       = (item) => item.type === "tour";
    const isHotelItem      = (item) => item.type === "hotel";
    const isTransportItem  = (item) => item.type === "transport";
    const isRestaurantItem = (item) => item.type === "restaurant";

    // ✅ Tính giá đúng theo field thực tế trong DB
    const calculateItemTotal = (item) => {
        if (isTourItem(item)) {
            // tours: price_adult, price_child, discount_price, discount_price_child
            const priceAdult = parseFloat(item.discount_price || item.price_adult || 0);
            const priceChild = parseFloat(item.discount_price_child || item.price_child || 0);
            const qtyAdult   = item.quantityAdult || 1;
            const qtyChild   = item.quantityChild || 0;
            return priceAdult * qtyAdult + priceChild * qtyChild;
        }
        if (isHotelItem(item)) {
            // hotels: price (per night), quantity (rooms), nights
            const pricePerNight = parseFloat(item.price || 0);
            const rooms  = item.quantity || 1;
            const nights = item.nights   || 1;
            return pricePerNight * rooms * nights;
        }
        if (isTransportItem(item)) {
            // transports: price (per day), quantity (cars), days
            const pricePerDay = parseFloat(item.price || 0);
            const cars = item.quantity || 1;
            const days = item.days     || 1;
            return pricePerDay * cars * days;
        }
        if (isRestaurantItem(item)) {
            // restaurants: price (per meal), oldprice
            const pricePerMeal = parseFloat(item.price || 0);
            if (item.adults !== undefined) {
                return (item.adults * pricePerMeal) + ((item.children || 0) * pricePerMeal * 0.5);
            }
            return pricePerMeal * (item.quantity || 1);
        }
        return 0;
    };

    const subtotal   = cartItems.reduce((sum, item) => sum + calculateItemTotal(item), 0);
    const vat        = subtotal * 0.05;
    const grandTotal = subtotal + vat;

    const formatVND = (val) => parseFloat(val || 0).toLocaleString("vi-VN") + "₫";

    const handleContinue = () => {
        const bookingInfo = {
            ...form,
            destination,
            checkInDate,
            checkOutDate,
            subtotal: subtotal.toFixed(2),
            tax:      vat.toFixed(2),
            total:    grandTotal.toFixed(2),
        };
        localStorage.setItem("BookingStepData", JSON.stringify(bookingInfo));
        setShowCheckout(true);
    };

    if (showCheckout) return (
        <PaymentPage
            cartItems={cartItems}
            bookingData={{
                ...form,
                destination,
                checkInDate,
                checkOutDate,
                subtotal: subtotal.toFixed(2),
                tax:      vat.toFixed(2),
                total:    grandTotal.toFixed(2),
            }}
        />
    );

    return (
        <div className="checkout-wrapper bg-dark text-white py-5">
            <div className="container">
                <div className="text-center mb-5">
                    <h2 className="fw-bold text-white">📑 Confirm Your Booking</h2>
                    <p className="text-white">Home → Cart → Checkout</p>
                    <div className="cartpage-steps d-flex justify-content-center align-items-center gap-3 my-3 steps">
                        <span className="step step-done step-circle completed">1</span>
                        <span className="step step-done step-circle completed">2</span>
                        <span className="step step-active step-circle current">3</span>
                        <span className="step step-circle">✓</span>
                    </div>
                </div>

                <div className="row g-4">
                    {/* CỘT TRÁI - Personal Details */}
                    <div className="col-lg-8">
                        <div className="p-4 rounded shadow bg-section-light">
                            <h5 className="text-warning mb-1">Personal Details</h5>

                            {profileLoaded && (
                                <p className="text-success small mb-3">
                                    <i className="ri-checkbox-circle-line me-1"></i>
                                    Đã tự điền từ profile của bạn — có thể chỉnh sửa bên dưới.
                                </p>
                            )}

                            <div className="row g-3">
                                <div className="col-md-6">
                                    <input name="fullName" value={form.fullName} onChange={handleChange}
                                        type="text" className="form-control dark-input" placeholder="Full Name" />
                                </div>
                                <div className="col-md-6">
                                    <input name="email" value={form.email} onChange={handleChange}
                                        type="email" className="form-control dark-input" placeholder="Email Address" />
                                </div>
                                <div className="col-md-6">
                                    <input name="phone" value={form.phone} onChange={handleChange}
                                        type="text" className="form-control dark-input" placeholder="Phone Number" />
                                </div>
                                <div className="col-md-6">
                                    <input name="country" value={form.country} onChange={handleChange}
                                        type="text" className="form-control dark-input" placeholder="Nationality (e.g. Việt Nam)" />
                                </div>
                                <div className="col-md-6">
                                    <input name="city" value={form.city} onChange={handleChange}
                                        type="text" className="form-control dark-input" placeholder="Address" />
                                </div>
                                <div className="col-12">
                                    <textarea name="notes" value={form.notes} onChange={handleChange}
                                        className="form-control dark-input" rows="3"
                                        placeholder="Additional Notes (Optional)" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* CỘT PHẢI - Booking Summary */}
                    <div className="col-lg-4">
                        <div className="p-4 rounded shadow-sm bg-section-light">
                            <h5 className="text-white mb-4">Booking Summary</h5>

                            <div className="mb-3">
                                <label className="text-white mb-1">Check-In</label>
                                <div className="input-group">
                                    <span className="input-group-text bg-dark border-0 text-warning">
                                        <i className="ri-calendar-line"></i>
                                    </span>
                                    <input type="date" value={checkInDate}
                                        onChange={(e) => setCheckInDate(e.target.value)}
                                        className="form-control dark-input" />
                                </div>
                            </div>

                            <div className="mb-3">
                                <label className="text-white mb-1">Check-Out</label>
                                <div className="input-group">
                                    <span className="input-group-text bg-dark border-0 text-warning">
                                        <i className="ri-calendar-line"></i>
                                    </span>
                                    <input type="date" value={checkOutDate}
                                        onChange={(e) => setCheckOutDate(e.target.value)}
                                        className="form-control dark-input" />
                                </div>
                            </div>

                            <p className="mb-3 text-light">
                                <i className="ri-map-pin-line text-warning me-2"></i>
                                Destination: {destination || "Chưa chọn điểm đến"}
                            </p>

                            <div className="p-3 bg-dark rounded mb-3 border border-secondary text-white">
                                <p>Sub Total <span className="float-end">{formatVND(subtotal)}</span></p>
                                <p>VAT (5%) <span className="float-end">{formatVND(vat)}</span></p>
                                <hr className="border-secondary" />
                                <p className="fw-bold fs-5">
                                    Total <span className="float-end text-warning">{formatVND(grandTotal)}</span>
                                </p>
                            </div>

                            <button type="button" className="btn next-btn w-100 fw-bold"
                                onClick={handleContinue}>
                                Continue & Next
                            </button>

                            <div className="text-white text-center small mt-3">
                                <i className="ri-checkbox-circle-line text-success me-1"></i>
                                Free Cancellation <br />
                                <small>Up to 24 hours in advance</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default CheckoutPage;