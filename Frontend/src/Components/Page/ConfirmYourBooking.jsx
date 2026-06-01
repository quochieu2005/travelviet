import { useEffect, useState } from 'react'

import PaymentPage from './Payment'
import { CartContext } from "../../context/CartContext";

function CheckoutPage({ cartItems = [], bookingDate }) {

    const [checkInDate, setCheckInDate] = useState(bookingDate || "");
    const [checkOutDate, setCheckOutDate] = useState("");
    const [showCheckout, setShowCheckout] = useState(false);

    const subtotal = cartItems.reduce((sum, item) => {
        const priceAdult = parseFloat(item["discount price"] || item["price adult"] || 0);
        const priceChild = parseFloat(item["discount price child"] || item["price child"] || 0);
        const qtyAdult = item.quantityAdult || 1;
        const qtyChild = item.quantityChild || 0;
        return sum + priceAdult * qtyAdult + priceChild * qtyChild;
    }, 0);

    const vat = subtotal * 0.05;
    const grandTotal = subtotal + vat;

    const formatVND = (val) => parseFloat(val || 0).toLocaleString("vi-VN") + "₫";

    const handleContinue = () => {
        const bookingInfo = {
            checkInDate,
            checkOutDate,
            subtotal: subtotal.toFixed(2),
            tax: vat.toFixed(2),
            total: grandTotal.toFixed(2),
        };

        localStorage.setItem("BookingStepData", JSON.stringify(bookingInfo));
        setShowCheckout(true);
    };

    if (showCheckout) return (
        <PaymentPage
            cartItems={cartItems}        
            bookingData={{               
                checkInDate,
                checkOutDate,
                subtotal: subtotal.toFixed(2),
                tax: vat.toFixed(2),
                total: grandTotal.toFixed(2),
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
                    {/* ✅ CỘT TRÁI - Personal Details */}
                    <div className="col-lg-8">
                        <div className="p-4 rounded shadow bg-section-light">
                            <h5 className="text-warning mb-4">Personal Details</h5>
                            <div className="row g-3">
                                <div className="col-md-6">
                                    <input type="text" className="form-control dark-input" placeholder="Full Name" />
                                </div>
                                <div className="col-md-6">
                                    <input type="email" className="form-control dark-input" placeholder="Email Address" />
                                </div>
                                <div className="col-md-6">
                                    <input type="number" className="form-control dark-input" placeholder="Phone Number" />
                                </div>

                                <div className="col-md-6">
                                    <input type="text" className="form-control dark-input" placeholder="Country" />
                                </div>
                                <div className="col-md-6">
                                    <input type="text" className="form-control dark-input" placeholder="City" />
                                </div>

                                <div className="col-12">
                                    <textarea className="form-control dark-input" rows="3" placeholder="Additional Notes (Optional)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* ✅ CỘT PHẢI - Booking Summary (ngang hàng với col-lg-8) */}
                    <div className="col-lg-4">
                        <div className="p-4 rounded shadow-sm bg-section-light">
                            <h5 className="text-white mb-4">Booking Summary</h5>

                            <div className="mb-3">
                                <label className="text-white mb-1">Check-In</label>
                                <div className="input-group">
                                    <span className="input-group-text bg-dark border-0 text-warning">
                                        <i className="ri-calendar-line"></i>
                                    </span>
                                    <input
                                        type="date"
                                        value={checkInDate}
                                        onChange={(e) => setCheckInDate(e.target.value)}
                                        className="form-control dark-input"
                                    />
                                </div>
                            </div>

                            <div className="mb-3">
                                <label className="text-white mb-1">Check-Out</label>
                                <div className="input-group">
                                    <span className="input-group-text bg-dark border-0 text-warning">
                                        <i className="ri-calendar-line"></i>
                                    </span>
                                    <input
                                        type="date"
                                        value={checkOutDate}
                                        onChange={(e) => setCheckOutDate(e.target.value)}
                                        className="form-control dark-input"
                                    />
                                </div>
                            </div>

                            <p className="mb-3 text-light">
                                <i className="ri-map-pin-line text-warning me-2"></i>
                                Destination: Bangkok, Thailand
                            </p>

                            <div className="p-3 bg-dark rounded mb-3 border border-secondary text-white">
                                <p>Sub Total <span className="float-end">{formatVND(subtotal)}</span></p>
                                <p>VAT (5%) <span className="float-end">{formatVND(vat)}</span></p>
                                <hr className="border-secondary" />
                                <p className="fw-bold fs-5">
                                    Total <span className="float-end text-warning">{formatVND(grandTotal)}</span>
                                </p>
                            </div>

                            <button
                                type="button"
                                className="btn next-btn w-100 fw-bold"
                                onClick={handleContinue}
                            >
                                Continue & Next
                            </button>

                            <div className="text-white text-center small mt-3">
                                <i className="ri-checkbox-circle-line text-success me-1"></i>
                                Free Cancellation <br />
                                <small>Up to 24 hours in advance</small>
                            </div>
                        </div>
                    </div>

                </div>{/* ✅ đóng row ở đây */}
            </div>
        </div>
    )
}

export default CheckoutPage
