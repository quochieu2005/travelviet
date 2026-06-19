import React, { useState, useContext, useEffect } from "react";
import CheckoutPage from "./ConfirmYourBooking";
import { CartContext } from "../../context/CartContext";

export default function Cart() {
  const { cartItems, RemoveFromCart, updateCart } = useContext(CartContext);
  const [showCheckout, setShowCheckout] = useState(false);
  const [bookingDate, setBookingDate] = useState(
    new Date().toISOString().split("T")[0]
  );

  // ✅ Lấy destination từ item đầu tiên trong cart
  const getDestinationName = () => {
    if (cartItems.length === 0) return "";
    const first = cartItems[0];
    return (
      first.destination_name ||         // nếu lưu thẳng tên
      first.destination?.name ||        // nếu là object
      first.location ||                 // fallback
      ""
    );
  };

  const destinationName = getDestinationName();

  const isTourItem = (item) => item.type === "tour";
  const isHotelItem = (item) => item.type === "hotel";
  const isTransportItem = (item) => item.type === "transport";
  const isRestaurantItem = (item) => item.type === "restaurant";

  const calculateItemTotal = (item) => {
    if (isTourItem(item)) {
      const priceAdult = parseFloat(item.discount_price || item.price_adult || 0);
      const priceChild = parseFloat(item.discount_price_child || item.price_child || 0);
      const qtyAdult = item.quantityAdult || 1;
      const qtyChild = item.quantityChild || 0;
      return priceAdult * qtyAdult + priceChild * qtyChild;
    }
    if (isHotelItem(item)) {
      const pricePerNight = parseFloat(item.price || 0);
      const rooms = item.quantity || 1;
      const nights = item.nights || 1;
      return pricePerNight * rooms * nights;
    }
    if (isTransportItem(item)) {
      const pricePerDay = parseFloat(item.price || 0);
      const cars = item.quantity || 1;
      const days = item.days || 1;
      return pricePerDay * cars * days;
    }
    if (isRestaurantItem(item)) {
      const pricePerMeal = parseFloat(item.price || 0);
      if (item.adults !== undefined) {
        return (item.adults * pricePerMeal) + ((item.children || 0) * pricePerMeal * 0.5);
      }
      return pricePerMeal * (item.quantity || 1);
    }
    return 0;
  };

  const getDisplayPrice = (item) => {
    if (isTourItem(item))      return formatVND(parseFloat(item.discount_price || item.price_adult || 0));
    if (isHotelItem(item))     return `${formatVND(item.price)} /đêm`;
    if (isTransportItem(item)) return `${formatVND(item.price)} /ngày`;
    if (isRestaurantItem(item))return `${formatVND(item.price)} /suất`;
    return formatVND(item.price);
  };

  const increaseQty = (item, field) => {
    if (isTourItem(item)) {
      if (field === "adult") updateCart(item.id, { quantityAdult: (item.quantityAdult || 1) + 1 });
      else updateCart(item.id, { quantityChild: (item.quantityChild || 0) + 1 });
    } else if (isHotelItem(item)) {
      updateCart(item.id, { quantity: (item.quantity || 1) + 1 });
    } else if (isTransportItem(item)) {
      updateCart(item.id, { quantity: (item.quantity || 1) + 1 });
    } else if (isRestaurantItem(item)) {
      if (item.adults !== undefined) {
        if (field === "adult") updateCart(item.id, { adults: (item.adults || 1) + 1 });
        else updateCart(item.id, { children: (item.children || 0) + 1 });
      } else {
        updateCart(item.id, { quantity: (item.quantity || 1) + 1 });
      }
    }
  };

  const decreaseQty = (item, field) => {
    if (isTourItem(item)) {
      if (field === "adult") {
        if ((item.quantityAdult || 1) > 1) updateCart(item.id, { quantityAdult: (item.quantityAdult || 1) - 1 });
        else RemoveFromCart(item.id, item.type);
      } else {
        if ((item.quantityChild || 0) > 0) updateCart(item.id, { quantityChild: (item.quantityChild || 0) - 1 });
      }
    } else if (isHotelItem(item)) {
      if ((item.quantity || 1) > 1) updateCart(item.id, { quantity: (item.quantity || 1) - 1 });
      else RemoveFromCart(item.id, item.type);
    } else if (isTransportItem(item)) {
      if ((item.quantity || 1) > 1) updateCart(item.id, { quantity: (item.quantity || 1) - 1 });
      else RemoveFromCart(item.id, item.type);
    } else if (isRestaurantItem(item)) {
      if (item.adults !== undefined) {
        if (field === "adult") {
          if ((item.adults || 1) > 1) updateCart(item.id, { adults: (item.adults || 1) - 1 });
          else RemoveFromCart(item.id, item.type);
        } else {
          if ((item.children || 0) > 0) updateCart(item.id, { children: (item.children || 0) - 1 });
        }
      } else {
        if ((item.quantity || 1) > 1) updateCart(item.id, { quantity: (item.quantity || 1) - 1 });
        else RemoveFromCart(item.id, item.type);
      }
    }
  };

  const renderQuantityControls = (item) => {
    if (isTourItem(item)) {
      const priceAdult = parseFloat(item.discount_price || item.price_adult || 0);
      const priceChild = parseFloat(item.discount_price_child || item.price_child || 0);
      const qtyAdult = item.quantityAdult || 1;
      const qtyChild = item.quantityChild || 0;
      return (
        <>
          <td>
            <div className="small text-muted mb-1">{formatVND(priceAdult)} / người</div>
            <div className="d-flex align-items-center gap-1">
              <button className="btn btn-sm btn-secondary" onClick={() => decreaseQty(item, "adult")}>-</button>
              <span className="mx-1">{qtyAdult}</span>
              <button className="btn btn-sm btn-secondary" onClick={() => increaseQty(item, "adult")}>+</button>
            </div>
          </td>
          <td>
            {priceChild > 0 ? (
              <>
                <div className="small text-muted mb-1">{formatVND(priceChild)} / trẻ</div>
                <div className="d-flex align-items-center gap-1">
                  <button className="btn btn-sm btn-secondary" onClick={() => decreaseQty(item, "child")}>-</button>
                  <span className="mx-1">{qtyChild}</span>
                  <button className="btn btn-sm btn-secondary" onClick={() => increaseQty(item, "child")}>+</button>
                </div>
              </>
            ) : (
              <span className="text-muted small">Không áp dụng</span>
            )}
          </td>
        </>
      );
    }
    if (isHotelItem(item)) {
      const qty = item.quantity || 1;
      const nights = item.nights || 1;
      return (
        <td colSpan="2">
          <div className="small text-muted mb-1">{qty} phòng × {nights} đêm</div>
          <div className="d-flex align-items-center gap-1">
            <button className="btn btn-sm btn-secondary" onClick={() => decreaseQty(item, "room")}>-</button>
            <span className="mx-1">{qty}</span>
            <button className="btn btn-sm btn-secondary" onClick={() => increaseQty(item, "room")}>+</button>
          </div>
        </td>
      );
    }
    if (isTransportItem(item)) {
      const qty = item.quantity || 1;
      const days = item.days || 1;
      return (
        <td colSpan="2">
          <div className="small text-muted mb-1">{qty} xe × {days} ngày</div>
          <div className="d-flex align-items-center gap-1">
            <button className="btn btn-sm btn-secondary" onClick={() => decreaseQty(item, "car")}>-</button>
            <span className="mx-1">{qty}</span>
            <button className="btn btn-sm btn-secondary" onClick={() => increaseQty(item, "car")}>+</button>
          </div>
        </td>
      );
    }
    if (isRestaurantItem(item)) {
      if (item.adults !== undefined) {
        return (
          <>
            <td>
              <div className="small text-muted mb-1">Người lớn</div>
              <div className="d-flex align-items-center gap-1">
                <button className="btn btn-sm btn-secondary" onClick={() => decreaseQty(item, "adult")}>-</button>
                <span className="mx-1">{item.adults || 1}</span>
                <button className="btn btn-sm btn-secondary" onClick={() => increaseQty(item, "adult")}>+</button>
              </div>
            </td>
            <td>
              <div className="small text-muted mb-1">Trẻ em</div>
              <div className="d-flex align-items-center gap-1">
                <button className="btn btn-sm btn-secondary" onClick={() => decreaseQty(item, "child")}>-</button>
                <span className="mx-1">{item.children || 0}</span>
                <button className="btn btn-sm btn-secondary" onClick={() => increaseQty(item, "child")}>+</button>
              </div>
            </td>
          </>
        );
      }
      const qty = item.quantity || 1;
      return (
        <td colSpan="2">
          <div className="small text-muted mb-1">{qty} suất ăn</div>
          <div className="d-flex align-items-center gap-1">
            <button className="btn btn-sm btn-secondary" onClick={() => decreaseQty(item, "meal")}>-</button>
            <span className="mx-1">{qty}</span>
            <button className="btn btn-sm btn-secondary" onClick={() => increaseQty(item, "meal")}>+</button>
          </div>
        </td>
      );
    }
    return <td colSpan="2"><span className="text-muted">N/A</span></td>;
  };

  const subtotal = cartItems.reduce((sum, item) => sum + calculateItemTotal(item), 0);
  const vat = subtotal * 0.05;
  const grandTotal = subtotal + vat;

  const formatVND = (val) => parseFloat(val || 0).toLocaleString("vi-VN") + "₫";

  // ✅ Chuyển sang CheckoutPage với đủ dữ liệu
  if (showCheckout)
    return (
      <CheckoutPage
        cartItems={cartItems}
        bookingDate={bookingDate}
        destination={destinationName}
      />
    );

  return (
    <div className="cartpage-wrapper">
      <div className="container cartpage-container">
        <div className="cartpage-header my-4">
          <h2 className="cartpage-title">Giỏ hàng của bạn</h2>
          <p className="cartpage-breadcrumb">Trang chủ → Giỏ hàng</p>
          <div className="cartpage-steps d-flex justify-content-center gap-2">
            <span className="step step-active">1</span>
            <span className="step">2</span>
            <span className="step">3</span>
            <span className="step">4</span>
          </div>
        </div>

        <div className="row cartpage-content">
          <div className="col-md-8 cartpage-cart">
            <h4>Chi tiết giỏ hàng</h4>

            {cartItems.length === 0 ? (
              <div className="cart-empty text-center p-4 bg-dark text-light rounded">
                <i className="ri-shopping-cart-2-line fs-1"></i>
                <h5>Giỏ hàng của bạn đang trống</h5>
                <p>Hãy thêm dịch vụ để bắt đầu đặt tour!</p>
                <div className="d-flex justify-content-center gap-2 flex-wrap">
                  <a href="/tours" className="btn btn-outline-warning">Đặt Tour</a>
                  <a href="/Hotels" className="btn btn-outline-primary">Đặt Khách sạn</a>
                  <a href="/transport" className="btn btn-outline-info">Thuê Xe</a>
                  <a href="/restaurants" className="btn btn-outline-success">Đặt Nhà hàng</a>
                </div>
              </div>
            ) : (
              <div className="table-responsive">
                <table className="table table-dark table-hover cart-table">
                  <thead className="table-light">
                    <tr>
                      <th>Sản phẩm</th>
                      <th>Số lượng / Người lớn</th>
                      <th>Trẻ em</th>
                      <th>Thành tiền</th>
                      <th>Xoá</th>
                    </tr>
                  </thead>
                  <tbody>
                    {cartItems.map((item, index) => {
                      const itemTotal = calculateItemTotal(item);
                      const itemType = item.type || (item.price_adult ? "tour" : "other");
                      return (
                        <tr key={`${item.id}-${itemType}-${index}`}>
                          <td className="align-middle">
                            <div className="d-flex align-items-center gap-3">
                              <img
                                src={item.image || item.thumbnail || "/placeholder.jpg"}
                                alt={item.title}
                                width="70"
                                height="70"
                                className="rounded object-fit-cover"
                                onError={(e) => (e.target.src = "/placeholder.jpg")}
                              />
                              <div>
                                <strong>{item.title}</strong>
                                <br />
                                {/* ✅ Hiển thị destination name */}
                                <small className="text-muted">
                                  {item.destination_name || item.destination?.name || item.location || ""}
                                </small>
                                <br />
                                <span className="badge bg-secondary text-capitalize mt-1">
                                  {itemType === "tour" ? "Tour" : item.type}
                                </span>
                                <div className="small text-muted mt-1">
                                  {getDisplayPrice(item)}
                                </div>
                              </div>
                            </div>
                          </td>

                          {renderQuantityControls(item)}

                          <td className="fw-bold text-warning align-middle">
                            {formatVND(itemTotal)}
                          </td>
                          <td className="align-middle text-center">
                            <i
                              className="ri-delete-bin-line text-danger fs-5"
                              role="button"
                              title="Xoá"
                              onClick={() => RemoveFromCart(item.id, item.type)}
                              style={{ cursor: "pointer" }}
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
                Tạm tính{" "}
                <span className="float-end text-success">{formatVND(subtotal)}</span>
              </h5>

              <p className="fw-bold mt-3">Chọn ngày khởi hành</p>
              <input
                type="date"
                value={bookingDate}
                onChange={(e) => setBookingDate(e.target.value)}
                min={new Date().toISOString().split("T")[0]}
                className="form-control mb-3"
              />

              {/* ✅ Hiển thị destination động */}
              <p>
                <i className="ri-map-pin-line me-2 text-warning"></i>
                {destinationName || "Điểm đến đã chọn"}
              </p>

              <div className="border-top pt-2 mt-2">
                <p>Tạm tính <span className="float-end">{formatVND(subtotal)}</span></p>
                <p>VAT (5%) <span className="float-end">{formatVND(vat)}</span></p>
                <hr />
                <h6>Tổng cộng <span className="float-end text-warning">{formatVND(grandTotal)}</span></h6>
              </div>

              <button
                className="btn next-btn w-100 fw-bold mt-3"
                disabled={cartItems.length === 0}
                onClick={() => setShowCheckout(true)}
              >
                Tiếp tục
              </button>

              <div className="mt-3 small">
                <i className="ri-checkbox-circle-line text-success me-1"></i>
                Huỷ miễn phí trước 24h
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}