import React, { useState, useContext, createContext } from "react";

import CheckoutPage from "./ConfirmYourBooking";
import { CartContext } from "../../context/CartContext";

export default function Cart() {
  const { cartItems, addToCart, RemoveFromCart, updateCart } = useContext(CartContext);
  const [showCheckout, setShowCheckout] = useState(false);
  const [bookingDate, setBookingDate] = useState(
    new Date().toISOString().split("T")[0],
  );

  const isTourItem = (item) =>
    item.type === "tour" || (!item.type && item["price adult"] !== undefined);

  const increaseQty = (id, type) => {
    const item = cartItems.find((i) => i.id === id);
    if (!item) return;
    if (type === "adult") {
      updateCart(id, { quantityAdult: (item.quantityAdult || 1) + 1 });
    } else {
      updateCart(id, { quantityChild: (item.quantityChild || 0) + 1 });
    }
  };

  const decreaseQty = (id, type) => {
    const item = cartItems.find((i) => i.id === id);
    if (!item) return;
    if (type === "adult") {
      if ((item.quantityAdult || 1) > 1) {
        updateCart(id, { quantityAdult: (item.quantityAdult || 1) - 1 });
      } else {
        RemoveFromCart(id);
      }
    } else {
      if ((item.quantityChild || 0) > 0) {
        updateCart(id, { quantityChild: (item.quantityChild || 0) - 1 });
      }
    }
  };

  const subtotal = cartItems.reduce((sum, item) => {
    if (isTourItem(item)) {
      const priceAdult = parseFloat(item["discount price"] || item["price adult"] || 0);
      const priceChild = parseFloat(item["discount price child"] || item["price child"] || 0);
      const qtyAdult = item.quantityAdult || 1;
      const qtyChild = item.quantityChild || 0;
      return sum + priceAdult * qtyAdult + priceChild * qtyChild;
    } else {
      // hotel, transport, restaurant — chỉ có item.price
      const qty = item.quantityAdult || item.quantity || 1;
      return sum + parseFloat(item.price || 0) * qty;
    }
  }, 0);

  const vat = subtotal * 0.05;
  const grandTotal = subtotal + vat;

  const formatVND = (val) => parseFloat(val || 0).toLocaleString("vi-VN") + "₫";

  if (showCheckout)
    return <CheckoutPage cartItems={cartItems} bookingDate={bookingDate} />;

  return (
    <>
      <div className="cartpage-wrapper">
        <div className="container cartpage-container">
          <div className="cartpage-header my-4">
            <h2 className="cartpage-title">Tour Cart Summary</h2>
            <p className="cartpage-breadcrumb">Home → Your Cart</p>
            <div className="cartpage-steps d-flex justify-content-center gap-2">
              <span className="step step-active">1</span>
              <span className="step">2</span>
              <span className="step">3</span>
              <span className="step">4</span>
            </div>
          </div>

          <div className="row cartpage-content">
            <div className="col-md-8 cartpage-cart">
              <h4>Your Cart Details</h4>

              {cartItems.length === 0 ? (
                <div className="cart-empty text-center p-4 bg-dark text-light rounded">
                  <i className="ri-shopping-cart-2-line fs-1"></i>
                  <h5>Your cart is currently empty</h5>
                  <p>Looks like you haven't added any bookings yet.</p>
                  <div className="d-flex justify-content-center gap-2">
                    <a href="/Hotels" className="btn btn-outline-warning">Browse Hotels</a>
                    <a href="/transport" className="btn btn-outline-primary">Book Transport</a>
                    <a href="/restaurants" className="btn btn-outline-success">Find Restaurants</a>
                  </div>
                </div>
              ) : (
                <div className="table-responsive">
                  <table className="table table-dark table-hover cart-table">
                    <thead className="table-light">
                      <tr>
                        <th>Package</th>
                        <th>Người lớn</th>
                        <th>Trẻ em</th>
                        <th>Thành tiền</th>
                        <th>Xoá</th>
                      </tr>
                    </thead>
                    <tbody>
                      {cartItems.map((item) => {
                        const tour = isTourItem(item);

                        const priceAdult = tour
                          ? parseFloat(item["discount price"] || item["price adult"] || 0)
                          : parseFloat(item.price || 0);

                        const priceChild = tour
                          ? parseFloat(item["discount price child"] || item["price child"] || 0)
                          : 0;

                        const qtyAdult = item.quantityAdult || 1;
                        const qtyChild = item.quantityChild || 0;
                        const itemTotal = priceAdult * qtyAdult + priceChild * qtyChild;

                        return (
                          <tr key={item.id}>
                            {/* Cột tên */}
                            <td className="d-flex align-items-center gap-3">
                              <img
                                src={item.image}
                                alt={item.title}
                                width="80"
                                className="rounded"
                                onError={(e) => (e.target.src = "/default-hotel.jpg")}
                              />
                              <div>
                                <strong>{item.title}</strong>
                                <br />
                                <small className="text-muted">{item.location}</small>
                                <br />
                                {item.duration && (
                                  <small className="text-capitalize text-warning">{item.duration}</small>
                                )}
                                {item.type && item.type !== "tour" && (
                                  <small className="text-capitalize text-info"> ({item.type})</small>
                                )}
                              </div>
                            </td>

                            {/* Cột người lớn / số lượng */}
                            <td>
                              <div className="small text-muted mb-1">
                                {formatVND(priceAdult)} / {tour ? "người" : "đơn vị"}
                              </div>
                              <div className="d-flex align-items-center gap-1">
                                <button
                                  className="btn btn-sm btn-secondary"
                                  onClick={() => decreaseQty(item.id, "adult")}
                                >-</button>
                                <span className="mx-1">{qtyAdult}</span>
                                <button
                                  className="btn btn-sm btn-secondary"
                                  onClick={() => increaseQty(item.id, "adult")}
                                >+</button>
                              </div>
                            </td>

                            {/* Cột trẻ em — chỉ hiện với tour có giá trẻ em */}
                            <td>
                              {tour && priceChild > 0 ? (
                                <>
                                  <div className="small text-muted mb-1">
                                    {formatVND(priceChild)} / trẻ
                                  </div>
                                  <div className="d-flex align-items-center gap-1">
                                    <button
                                      className="btn btn-sm btn-secondary"
                                      onClick={() => decreaseQty(item.id, "child")}
                                    >-</button>
                                    <span className="mx-1">{qtyChild}</span>
                                    <button
                                      className="btn btn-sm btn-secondary"
                                      onClick={() => increaseQty(item.id, "child")}
                                    >+</button>
                                  </div>
                                </>
                              ) : (
                                <span className="text-muted small">N/A</span>
                              )}
                            </td>

                            {/* Thành tiền */}
                            <td className="fw-bold text-warning">{formatVND(itemTotal)}</td>

                            {/* Xoá */}
                            <td>
                              <i
                                className="ri-delete-bin-line text-danger fs-5"
                                role="button"
                                title="Remove item"
                                onClick={() => RemoveFromCart(item.id)}
                              />
                            </td>
                          </tr>
                        );
                      })}
                    </tbody>
                  </table>
                </div>
              )}
            </div>

            <div className="col-md-4 mt-4 mt-md-0">
              <div className="p-3 bg-dark text-light rounded">
                <h5>
                  Total{" "}
                  <span className="float-end text-success">{formatVND(subtotal)}</span>
                </h5>

                <p className="fw-bold mt-3">Select Travel Date</p>
                <input
                  type="date"
                  value={bookingDate}
                  onChange={(e) => setBookingDate(e.target.value)}
                  min={new Date().toISOString().split("T")[0]}
                  className="form-control mb-3"
                />

                <p>
                  <i className="ri-map-pin-line me-2 text-warning"></i>
                  Destination Selected
                </p>

                <div className="border-top pt-2 mt-2">
                  <p>
                    Subtotal
                    <span className="float-end">{formatVND(subtotal)}</span>
                  </p>
                  <p>
                    VAT (5%)
                    <span className="float-end">{formatVND(vat)}</span>
                  </p>
                  <hr />
                  <h6>
                    Grand Total
                    <span className="float-end text-warning">{formatVND(grandTotal)}</span>
                  </h6>
                </div>

                <button
                  className="btn next-btn w-100 fw-bold mt-3"
                  disabled={cartItems.length === 0}
                  onClick={() => setShowCheckout(true)}
                >
                  Continue & Next
                </button>

                <div className="mt-3 small">
                  <i className="ri-checkbox-circle-line text-success me-1"></i>
                  Free cancellation up to 24h in advance
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
