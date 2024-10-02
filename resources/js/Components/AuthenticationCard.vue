<style>
body{
    overflow: hidden;
}</style>
<template>
    <div class="login-container">
        <div class="background-image"></div>
        <div class="login-content">
            <div class="login-card">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
::-webkit-scrollbar{
    width: 0px;
    height: 4px;
}
    .login-container {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
    }

    .background-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%; /* Increase the width to ensure coverage */
        height: 100%;
        z-index: -1;
        background-image: url('/you2.jpg'); /* Adjust the path to your image */
        background-size: cover;
        background-position: center;
         
    }
  

    .login-content {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
         overflow: hidden;
    }

   .login-card {
    width: 90%; /* Adjust width as per your design */
    max-width: 440px; /* Adjust max-width as per your design */
    padding: 40px; /* Increase padding for larger login content */
    border-radius: 5%; /* Adjust border radius for a circular shape */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Adjust box shadow as per your design */
    overflow: hidden;
    position: relative;
}

.login-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 200%;
    height: 200%;
   background: linear-gradient(45deg, #A7C9D7, #B3E5FC, #81D4FA);
    background-size: 400% 400%;
    animation: flowBackground 10s infinite linear;
    z-index: -1;
    transition: opacity 0.3s ease;
}
</style>
