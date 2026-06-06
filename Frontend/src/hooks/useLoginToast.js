import { useEffect } from 'react';
import { toast } from 'react-toastify';

export function useLoginToast() {
    useEffect(() => {
        const flag = sessionStorage.getItem('loginSuccess');
        if (flag) {
            sessionStorage.removeItem('loginSuccess');
            toast.success('Đăng nhập thành công! Chào mừng bạn trở lại 👋', {
                autoClose: 2500,
                position: "top-right"
            });
        }
    }, []);
}