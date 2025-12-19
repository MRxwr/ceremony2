<style>
    @import url('https://fonts.googleapis.com/css2?family=Fustat:wght@200..800&display=swap');
    
    :root {
        --rose: #F8D7DA;
        --blush: #FDE2E4;
        --cream: #FFF8F3;
        --gold: #D4AF37;
        --soft-pink: #FADCD9;
        --text-dark: #2D2D2D;
        --text-light: #666666;
        --card-shadow: 0 20px 60px rgba(0,0,0,0.1);
    }

    loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Inter', sans-serif;
        color: var(--text-dark);
        background: linear-gradient(135deg, var(--cream) 0%, var(--blush) 100%);
        min-height: 100vh;
        overflow-x: hidden;
    }
    
    [dir="rtl"] body {
        font-family: 'Fustat', sans-serif;
    }
    
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(circle at 20% 80%, rgba(255,182,193,0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255,218,185,0.3) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(255,228,225,0.2) 0%, transparent 50%);
        pointer-events: none;
        z-index: -1;
    }
    
    /* Main Container */
    .main-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
    }
    
    .floating-hearts {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
    }
    
    .floating-heart {
        position: absolute;
        color: rgba(212, 175, 55, 0.3);
        animation: floatUp 15s infinite;
    }
    
    @keyframes floatUp {
        0% {
            transform: translateY(100vh) rotate(0deg);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            transform: translateY(-100vh) rotate(360deg);
            opacity: 0;
        }
    }
    
    [dir="rtl"] .form-select {
        background-position: left 1rem center;
        text-align: right;
    }
</style>