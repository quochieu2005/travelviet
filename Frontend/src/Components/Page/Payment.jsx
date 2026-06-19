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
    const [selectedMethod, setSelectedMethod] = useState("card");

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

    return (
        <div className="payment-wrapper">
            <div className="container">
                {/* Header with Steps */}
                <div className="payment-header">
                    <h2>Payment</h2>
                    <div className="payment-steps">
                        <div className="step completed">
                            <div className="step-circle">1</div>
                            <span>Cart</span>
                        </div>
                        <div className="step-line"></div>
                        <div className="step completed">
                            <div className="step-circle">2</div>
                            <span>Information</span>
                        </div>
                        <div className="step-line"></div>
                        <div className="step active">
                            <div className="step-circle">3</div>
                            <span>Payment</span>
                        </div>
                    </div>

                    <div className="payment-content">
                        {/* LEFT COLUMN - Payment Forms */}
                        <div className="payment-left">
                            {/* Method Selection Tabs */}
                            <div className="method-tabs">
                                <button
                                    className={`method-tab ${selectedMethod === "card" ? "active" : ""}`}
                                    onClick={() => setSelectedMethod("card")}
                                >
                                    <i className="ri-bank-card-line"></i>
                                    Credit / Debit Card
                                </button>
                                <button
                                    className={`method-tab ${selectedMethod === "online" ? "active" : ""}`}
                                    onClick={() => setSelectedMethod("online")}
                                >
                                    <i className="ri-global-line"></i>
                                    Online Payment
                                </button>
                            </div>

                            {/* Card Payment Form */}
                            {selectedMethod === "card" && (
                                <div className="payment-form">
                                    <div className="form-group">
                                        <label>Name On Card</label>
                                        <input
                                            type="text"
                                            placeholder="Mr. Alexa"
                                            value={cardName}
                                            onChange={(e) => setCardName(e.target.value)}
                                        />
                                    </div>

                                    <div className="form-group">
                                        <label>Card Number</label>
                                        <input
                                            type="text"
                                            placeholder="1234 5678 9012 3456"
                                            value={cardNumber}
                                            onChange={(e) => setCardNumber(formatCardNumber(e.target.value))}
                                            maxLength={19}
                                        />
                                    </div>

                                    <div className="form-row">
                                        <div className="form-group">
                                            <label>Expiration Date</label>
                                            <input
                                                type="text"
                                                placeholder="MM/YY"
                                                value={expiry}
                                                onChange={(e) => setExpiry(formatExpiry(e.target.value))}
                                                maxLength={5}
                                            />
                                        </div>
                                        <div className="form-group">
                                            <label>CVV / CVC</label>
                                            <input
                                                type="text"
                                                placeholder="123"
                                                value={ccv}
                                                onChange={(e) => setCcv(e.target.value.replace(/\D/g, "").slice(0, 4))}
                                                maxLength={4}
                                            />
                                        </div>
                                    </div>

                                    <label className="checkbox-label">
                                        <input
                                            type="checkbox"
                                            checked={agreeCard}
                                            onChange={(e) => setAgreeCard(e.target.checked)}
                                        />
                                        <span>I agree to the <a href="#">Terms and Policy</a> and <a href="#">Privacy Policy</a></span>
                                    </label>

                                    <button className="btn-pay" onClick={handleCardPayment}>
                                        Payment Now
                                    </button>
                                </div>
                            )}

                            {/* Online Payment Form */}
                            {selectedMethod === "online" && (
                                <div className="payment-form">
                                    <div className="online-methods">
                                        <div className="method-item" onClick={() => handleOnlinePayment("PayPal")}>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" />
                                            <span>PayPal</span>
                                        </div>
                                        <div className="method-item" onClick={() => handleOnlinePayment("Stripe")}>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" alt="Stripe" />
                                            <span>Stripe</span>
                                        </div>
                                        <div className="method-item" onClick={() => handleOnlinePayment("Mastercard")}>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" />
                                            <span>Mastercard</span>
                                        </div>
                                        <div className="method-item" onClick={() => handleOnlinePayment("Visa")}>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" />
                                            <span>Visa</span>
                                        </div>
                                    </div>

                                    <label className="checkbox-label">
                                        <input
                                            type="checkbox"
                                            checked={agreeOnline}
                                            onChange={(e) => setAgreeOnline(e.target.checked)}
                                        />
                                        <span>I agree to the <a href="#">Terms and Policy</a> and <a href="#">Privacy Policy</a></span>
                                    </label>

                                    <button className="btn-pay" onClick={() => handleOnlinePayment("Online")}>
                                        Payment Now
                                    </button>
                                </div>
                            )}
                        </div>

                        {/* RIGHT COLUMN - Order Summary */}
                        <div className="payment-right">
                            <div className="summary-card">
                                <h3>Order Summary</h3>

                                <div className="summary-info">
                                    <div className="info-row">
                                        <span className="label">Travel Date</span>
                                        <span className="value">{travelDate || "Not selected"}</span>
                                    </div>
                                    <div className="info-row">
                                        <span className="label">Destination</span>
                                        <span className="value">Bangkok, Thailand</span>
                                    </div>
                                </div>

                                <div className="summary-divider"></div>

                                <div className="price-breakdown">
                                    <div className="price-row">
                                        <span>Subtotal</span>
                                        <span>{formatVND(subtotal)}</span>
                                    </div>
                                    <div className="price-row">
                                        <span>VAT (10%)</span>
                                        <span>{formatVND(vat)}</span>
                                    </div>
                                    {cartItems.map((item, idx) => (
                                        <div key={idx} className="price-row">
                                            <span>{item.title || item.name || `Item ${idx + 1}`} x{item.quantityAdult || 1}</span>
                                            <span>{formatVND((item["discount price"] || item["price adult"] || 0) * (item.quantityAdult || 1))}</span>
                                        </div>
                                    ))}
                                    <div className="price-row">
                                        <span>Adults x {adults}</span>
                                        <span>Included</span>
                                    </div>
                                    {children > 0 && (
                                        <div className="price-row">
                                            <span>Children x {children}</span>
                                            <span>Included</span>
                                        </div>
                                    )}
                                    {tourGuide > 0 && (
                                        <div className="price-row">
                                            <span>Tour Guide</span>
                                            <span>{formatVND(tourGuide)}</span>
                                        </div>
                                    )}
                                    {dinner > 0 && (
                                        <div className="price-row">
                                            <span>Dinner</span>
                                            <span>{formatVND(dinner)}</span>
                                        </div>
                                    )}
                                </div>

                                <div className="summary-divider"></div>

                                <div className="total-row">
                                    <span>Total</span>
                                    <span>{formatVND(grandTotal)}</span>
                                </div>

                                <button className="btn-booknow" onClick={handleCardPayment}>
                                    Complete Booking
                                </button>

                                <div className="secure-note">
                                    <i className="ri-lock-line"></i>
                                    Secure payment encrypted
                                </div>

                                <div className="cancellation-note">
                                    <i className="ri-refund-line"></i>
                                    Free cancellation up to 24 hours in advance
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Loading overlay khi thanh toán */}
                {paid && (
                    <div className="payment-loading">
                        <div className="spinner"></div>
                        <p>Processing payment...</p>
                    </div>
                )}
            </div>
        </div>
    );
}

export default Payment;