// import useAuthStore from '@/store/authStore';

// const authStore = useAuthStore()

export const role = {
    mounted(el, binding, vnode) {
        const { value } = binding;

        if (value[0] && value[0] instanceof Array && value[0].length > 0) {
            const requiredRoles = value[0];
            const hasRole = value[1].some(role => {
                return requiredRoles.includes(role);
            });

            if (!hasRole) {
                el.parentNode && el.parentNode.removeChild(el);
            }
        } else {
            throw new Error(`Roles are required! Example: v-role="['admin','editor']"`);
        }
    },
};