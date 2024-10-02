// import useAuthStore from '@/store/authStore';

// const authStore = useAuthStore()

export const permission = {
    mounted(el, binding, vnode) {
        const { value } = binding;

        if (value[0] && value[0] instanceof Array && value[0].length > 0) {
            const requiredPermissions = value[0];
            const hasPermission = value[1].some(permission => {
                return requiredPermissions.includes(permission);
            });

            if (!hasPermission) {
                el.parentNode && el.parentNode.removeChild(el);
            }
        } else {
            throw new Error(`Permissions are required! Example: v-permission="['manage user','manage permission']"`);
        }
    },
};

//  default permission;