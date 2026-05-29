import  {useEffect , useState} from 'react'

// import PaymentPage from './Payment'
import { CartContext } from "../../context/CartContext";

function CheckoutPage() {

    const [cartItems , setCartItems] = useState([]);
    const [checkInDate , setCheckInDate] = useState["2026-05-29"];
    const [checkOutDate , setCheckOutDate] = useState["2026-06-02"];
    const [showCheckout , setShowCheckout] = useState(false);

    useEffect(() => {
        const storedCart = JSON.parse(localStorage.getItem('cart')) || [];
        setCartItems(storedCart);
    } , []);

    const calculateSubtotal = () => {
        return cartItems.reduce((total , item ) => total + item.price , 0);
    };

    const calculateTax = (subtotal) => subtotal * 0.05;
    const calculateTotal = () => {
        const subtotal = calculateSubtotal();
        return subtotal + calculateTax(subtotal);
    };

    const handleContinue = () => {
        const bookingInfo = {
            checkInDate,
            checkOutDate,
            subtotal: calculateSubtotal().toFixed(2),
            tax : calculateTax(calculateSubtotal()).toFixed(2),
            total : calculateTotal.toFixed(2),
        };

        localStorage.setItem("BookingStepData" , JSON.stringify(bookingInfo));
        setShowCheckout(true);
    };

    if(showCheckout) return <Payment />

  return (
    <>
        
        <div className="checkout-wrapper bg-dark text-white py-5">
            <div className="container">
                <div className="text-center mb-5">
                    <h2 className="fw-bold text-white">Confirm Your Booking</h2>
                </div>
            </div>
        </div>

    </>
  )
}

export default CheckoutPage
