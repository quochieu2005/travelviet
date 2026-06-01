import { useState } from "react";
import { useNavigate } from "react-router-dom";

function Payment({ cartItems = [], bookingData = {} }) {
    const navigate = useNavigate();
    const [cardName, setCardName] = useState("");
    const [cardNumber, setCardNumber] = useState("");
    const [expiry, setExpiry] = useState("");
    const [ccv, setCcv] = useState("");
    const [agreeCard, setAgreeCard] = useState(false);
    const [agreeOnline, setAgreeOnline] = useState(false);
    const [paid, setPaid] = useState(false);

    // Lấy data từ localStorage nếu không có props
    const stored = (() => {
        try { return JSON.parse(localStorage.getItem("BookingStepData") || "{}"); }
        catch { return {}; }
    })();

    const subtotal = parseFloat(bookingData.subtotal || stored.subtotal || 0);
    const vat = parseFloat(bookingData.tax || stored.tax || 0);
    const grandTotal = parseFloat(bookingData.total || stored.total || 0);
    const travelDate = bookingData.checkInDate || stored.checkInDate || "";
    const adults = cartItems.reduce((s, i) => s + (i.quantityAdult || 1), 0);
    const children = cartItems.reduce((s, i) => s + (i.quantityChild || 0), 0);

    // Thêm khai báo cho tourGuide và dinner
    const tourGuide = bookingData.tourGuide || stored.tourGuide || 0;
    const dinner = bookingData.dinner || stored.dinner || 0;

    const formatCardNumber = (val) => {
        const digits = val.replace(/\D/g, "").slice(0, 16);
        return digits.replace(/(.{4})/g, "$1 ").trim();
    };

    const formatExpiry = (val) => {
        const digits = val.replace(/\D/g, "").slice(0, 4);
        if (digits.length >= 3) return digits.slice(0, 2) + "/" + digits.slice(2);
        return digits;
    };

    const formatVND = (val) => parseFloat(val || 0).toLocaleString("vi-VN") + "₫";

    const handleCardPayment = () => {
        if (!cardName || !cardNumber || !expiry || !ccv) {
            alert("Vui lòng điền đầy đủ thông tin thẻ!");
            return;
        }
        if (!agreeCard) {
            alert("Vui lòng đồng ý với Terms và Privacy Policy!");
            return;
        }
        setPaid(true);
        setTimeout(() => {
            navigate("/booking-confirmation", {
                state: {
                    date: travelDate,
                    location: "Bangkok, Thailand",
                    adults,
                    children,
                    tourGuide,
                    dinner,
                    tax: vat,
                    subTotal: subtotal,
                    total: grandTotal,
                    showInvoiceButton: true,
                }
            });
        }, 1500);
    };

    const handleOnlinePayment = (method) => {
        if (!agreeOnline) {
            alert("Vui lòng đồng ý với Terms và Privacy Policy!");
            return;
        }
        alert(`Redirecting to ${method}...`);
        setTimeout(() => {
            navigate("/booking-confirmation");
        }, 1500);
    };

    return (
        <div className="payment-wrapper bg-dark text-white py-5">
            <div className="container">
                {/* Header */}
                <div className="text-center mb-5">
                    <h2 className="fw-bold text-white mb-2">Place Your Order</h2>
                    <div className="cartpage-steps d-flex justify-content-center align-items-center gap-3 my-3 steps">
                        <span className="step step-done step-circle completed">1</span>
                        <span className="step step-done step-circle completed">2</span>
                        <span className="step step-done step-circle completed">3</span>
                        <span className="step step-active step-circle current">✓</span>
                    </div>
                </div>

                <div className="row g-4">
                    {/* LEFT - Payment Forms */}
                    <div className="col-lg-8">

                        {/* Card Payment */}
                        <div className="payment-card">
                            <div className="section-title">Cart Payment</div>
                            <div className="mb-3">
                                <label className="text-white-50 mb-1 small">Name On Card</label>
                                <input
                                    type="text"
                                    className="payment-input"
                                    placeholder="Mr. Alexa"
                                    value={cardName}
                                    onChange={(e) => setCardName(e.target.value)}
                                />
                            </div>
                            <div className="mb-3">
                                <label className="text-white-50 mb-1 small">Card Number</label>
                                <input
                                    type="text"
                                    className="payment-input"
                                    placeholder="1234 5678 9012 3456"
                                    value={cardNumber}
                                    onChange={(e) => setCardNumber(formatCardNumber(e.target.value))}
                                    maxLength={19}
                                />
                            </div>
                            <div className="row g-3 mb-3">
                                <div className="col-md-6">
                                    <label className="text-white-50 mb-1 small">Expiration Date</label>
                                    <input
                                        type="text"
                                        className="payment-input"
                                        placeholder="MM/YY"
                                        value={expiry}
                                        onChange={(e) => setExpiry(formatExpiry(e.target.value))}
                                        maxLength={5}
                                    />
                                </div>
                                <div className="col-md-6">
                                    <label className="text-white-50 mb-1 small">CCV</label>
                                    <input
                                        type="text"
                                        className="payment-input"
                                        placeholder="123"
                                        value={ccv}
                                        onChange={(e) => setCcv(e.target.value.replace(/\D/g, "").slice(0, 4))}
                                        maxLength={4}
                                    />
                                </div>
                            </div>
                            <label className="custom-checkbox mb-3">
                                <input
                                    type="checkbox"
                                    checked={agreeCard}
                                    onChange={(e) => setAgreeCard(e.target.checked)}
                                />
                                I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
                            </label>
                            <div>
                                <button className="btn-pay" onClick={handleCardPayment}>
                                    Payment Now
                                </button>
                            </div>
                        </div>

                        {/* Online Payment */}
                        <div className="payment-card">
                            <div className="section-title">Online Payment</div>
                            <div className="d-flex align-items-center gap-3 flex-wrap mb-4">
                                {[
                                    { name: "PayPal", src: "https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" },
                                    { name: "Stripe", src: "https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" },
                                    { name: "Mastercard", src: "https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" },
                                    { name: "Visa", src: "https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" },
                                ].map((p) => (
                                    <img
                                        key={p.name}
                                        src={p.src}
                                        alt={p.name}
                                        className="online-logo"
                                        onClick={() => handleOnlinePayment(p.name)}
                                        title={`Pay with ${p.name}`}
                                    />
                                ))}
                            </div>
                            <label className="custom-checkbox mb-3">
                                <input
                                    type="checkbox"
                                    checked={agreeOnline}
                                    onChange={(e) => setAgreeOnline(e.target.checked)}
                                />
                                I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
                            </label>
                            <div>
                                <button className="btn-pay" onClick={() => handleOnlinePayment("Online")}>
                                    Payment Now
                                </button>
                            </div>
                        </div>

                    </div>

                    {/* RIGHT - Order Summary */}
                    <div className="col-lg-4">
                        <div className="summary-card">
                            <div className="d-flex justify-content-between align-items-center mb-4">
                                <span className="fw-bold fs-5 text-white">Total</span>
                                <span className="fw-bold fs-5 text-white">{formatVND(grandTotal)}</span>
                            </div>

                            <hr className="divider" />

                            {travelDate && (
                                <div className="mb-3">
                                    <div className="text-white-50 small mb-1">Travel Date</div>
                                    <div className="d-flex align-items-center gap-2 text-white">
                                        <i className="ri-calendar-line text-warning"></i>
                                        {travelDate}
                                    </div>
                                </div>
                            )}

                            <div className="mb-3">
                                <div className="text-white-50 small mb-1">Selected Destination</div>
                                <div className="d-flex align-items-center gap-2 text-white">
                                    <i className="ri-map-pin-line text-warning"></i>
                                    Bangkok, Thailand
                                </div>
                            </div>

                            <hr className="divider" />

                            <div className="summary-row">
                                <span>Sub Total</span>
                                <span>{formatVND(subtotal)}</span>
                            </div>
                            <div className="summary-row">
                                <span>VAT Tax</span>
                                <span>{formatVND(vat)}</span>
                            </div>
                            <div className="summary-row">
                                <span>Adults</span>
                                <span>{adults}</span>
                            </div>
                            <div className="summary-row">
                                <span>Children</span>
                                <span>{children}</span>
                            </div>
                            <div className="summary-row">
                                <span>Tour Guide</span>
                                <span>{formatVND(tourGuide)}</span>
                            </div>
                            <div className="summary-row">
                                <span>Dinner</span>
                                <span>{formatVND(dinner)}</span>
                            </div>

                            {cartItems.map((item, i) => (
                                <div key={i} className="summary-row">
                                    <span>{item.title || item.name || `Item ${i + 1}`}</span>
                                    <span>{formatVND((item["discount price"] || item["price adult"] || 0) * (item.quantityAdult || 1))}</span>
                                </div>
                            ))}

                            <hr className="divider" />

                            <div className="summary-row total-row">
                                <span>Total</span>
                                <span>{formatVND(grandTotal)}</span>
                            </div>

                            <button className="btn-booknow mt-4" onClick={handleCardPayment}>
                                Book Now
                            </button>

                            <div className="text-center mt-3 text-white-50 small">
                                <span className="text-success me-1">✔</span>
                                Free Cancellation<br />
                                <small>Up to 24 hours in advance</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    );
}

export default Payment;