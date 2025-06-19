/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
          950: '#172554'
        },
        secondary: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
          950: '#020617'
        },
        accent: {
          50: '#faf5ff',
          100: '#f3e8ff',
          200: '#e9d5ff',
          300: '#d8b4fe',
          400: '#c084fc',
          500: '#a855f7',
          600: '#9333ea',
          700: '#7c3aed',
          800: '#6b21a8',
          900: '#581c87',
          950: '#3b0764'
        },
        success: {
          50: '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#22c55e',
          600: '#16a34a',
          700: '#15803d',
          800: '#166534',
          900: '#14532d'
        },
        warning: {
          50: '#fffbeb',
          100: '#fef3c7',
          200: '#fde68a',
          300: '#fcd34d',
          400: '#fbbf24',
          500: '#f59e0b',
          600: '#d97706',
          700: '#b45309',
          800: '#92400e',
          900: '#78350f'
        },
        error: {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: '#ef4444',
          600: '#dc2626',
          700: '#b91c1c',
          800: '#991b1b',
          900: '#7f1d1d'
        },
        info: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a'
        },
        // Alias legacy per compatibilità
        dark: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
          950: '#020617'
        }
      },
      fontFamily: {
        'sans': ['Inter', 'system-ui', 'sans-serif'],
        'mono': ['JetBrains Mono', 'Menlo', 'Monaco', 'monospace'],
        'display': ['Inter', 'system-ui', 'sans-serif']
      },
      fontSize: {
        'xs': ['0.75rem', { lineHeight: '1rem' }],
        'sm': ['0.875rem', { lineHeight: '1.25rem' }],
        'base': ['1rem', { lineHeight: '1.5rem' }],
        'lg': ['1.125rem', { lineHeight: '1.75rem' }],
        'xl': ['1.25rem', { lineHeight: '1.75rem' }],
        '2xl': ['1.5rem', { lineHeight: '2rem' }],
        '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
        '5xl': ['3rem', { lineHeight: '1' }],
        '6xl': ['3.75rem', { lineHeight: '1' }],
        '7xl': ['4.5rem', { lineHeight: '1' }],
        '8xl': ['6rem', { lineHeight: '1' }],
        '9xl': ['8rem', { lineHeight: '1' }],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '100': '25rem',
        '112': '28rem',
        '128': '32rem',
      },
      animation: {
        // Animazioni base migliorate
        'fade-in': 'fadeIn 0.8s ease-out',
        'fade-in-up': 'fadeInUp 0.6s ease-out',
        'fade-in-down': 'fadeInDown 0.6s ease-out',
        'fade-in-left': 'fadeInLeft 0.6s ease-out',
        'fade-in-right': 'fadeInRight 0.6s ease-out',
        'slide-up': 'slideUp 0.6s ease-out',
        'slide-down': 'slideDown 0.6s ease-out',
        'slide-left': 'slideLeft 0.6s ease-out',
        'slide-right': 'slideRight 0.6s ease-out',
        'bounce-slow': 'bounce 2s infinite',
        'bounce-fast': 'bounce 0.5s infinite',
        
        // Animazioni typing
        'typing': 'typing 3.5s steps(40, end), blink-caret .75s step-end infinite',
        'blink': 'blink 1s infinite',
        
        // Animazioni gradient
        'gradient': 'gradient 15s ease infinite',
        'gradient-fast': 'gradient 3s ease infinite',
        'gradient-slow': 'gradient 8s ease infinite',
        
        // Animazioni movimento
        'float': 'float 6s ease-in-out infinite',
        'float-slow': 'float 8s ease-in-out infinite',
        'float-fast': 'float 4s ease-in-out infinite',
        'sway': 'sway 3s ease-in-out infinite',
        'dance': 'dance 2s ease-in-out infinite',
        
        // Animazioni particelle
        'particles': 'particles 20s linear infinite',
        'particles-slow': 'particles 30s linear infinite',
        'particles-fast': 'particles 15s linear infinite',
        
        // Animazioni scale e rotate
        'scale-in': 'scaleIn 0.5s ease-out',
        'scale-out': 'scaleOut 0.5s ease-out',
        'rotate-slow': 'rotateSlow 8s linear infinite',
        'rotate-fast': 'rotateFast 2s linear infinite',
        
        // Animazioni glow
        'glow': 'glow 2s ease-in-out infinite alternate',
        'glow-pulse': 'glowPulse 3s ease-in-out infinite',
        
        // Animazioni shimmer
        'shimmer': 'shimmer 2s infinite',
        'shimmer-slow': 'shimmer 3s infinite',
        
        // Animazioni morphing
        'morph': 'morph 4s ease-in-out infinite',
        'wave': 'wave 2s ease-in-out infinite',
        
        // Animazioni loading
        'skeleton': 'skeleton 1.5s infinite',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'ping-slow': 'ping 2s cubic-bezier(0, 0, 0.2, 1) infinite',
        
        // Animazioni hover
        'hover-float': 'hoverFloat 0.3s ease-out',
        'hover-glow': 'hoverGlow 0.3s ease-out',
        'hover-scale': 'hoverScale 0.3s ease-out'
      },
      keyframes: {
        // Keyframes base
        fadeIn: {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' }
        },
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' }
        },
        fadeInDown: {
          '0%': { opacity: '0', transform: 'translateY(-30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' }
        },
        fadeInLeft: {
          '0%': { opacity: '0', transform: 'translateX(-30px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' }
        },
        fadeInRight: {
          '0%': { opacity: '0', transform: 'translateX(30px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' }
        },
        slideUp: {
          '0%': { opacity: '0', transform: 'translateY(50px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' }
        },
        slideDown: {
          '0%': { opacity: '0', transform: 'translateY(-50px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' }
        },
        slideLeft: {
          '0%': { opacity: '0', transform: 'translateX(50px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' }
        },
        slideRight: {
          '0%': { opacity: '0', transform: 'translateX(-50px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' }
        },
        
        // Keyframes typing
        typing: {
          'from': { width: '0' },
          'to': { width: '100%' }
        },
        'blink-caret': {
          'from, to': { 'border-color': 'transparent' },
          '50%': { 'border-color': '#3b82f6' }
        },
        blink: {
          '0%, 50%': { opacity: '1' },
          '51%, 100%': { opacity: '0' }
        },
        
        // Keyframes gradient
        gradient: {
          '0%, 100%': { 'background-position': '0% 50%' },
          '50%': { 'background-position': '100% 50%' }
        },
        
        // Keyframes movimento
        float: {
          '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
          '25%': { transform: 'translateY(-10px) rotate(1deg)' },
          '50%': { transform: 'translateY(-20px) rotate(0deg)' },
          '75%': { transform: 'translateY(-10px) rotate(-1deg)' }
        },
        sway: {
          '0%, 100%': { transform: 'rotate(0deg)' },
          '25%': { transform: 'rotate(3deg)' },
          '75%': { transform: 'rotate(-3deg)' }
        },
        dance: {
          '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
          '25%': { transform: 'translateY(-5px) rotate(5deg)' },
          '50%': { transform: 'translateY(-10px) rotate(0deg)' },
          '75%': { transform: 'translateY(-5px) rotate(-5deg)' }
        },
        
        // Keyframes particelle
        particles: {
          '0%': { transform: 'translateY(100vh) rotate(0deg)', opacity: '0' },
          '10%': { opacity: '0.7' },
          '90%': { opacity: '0.7' },
          '100%': { transform: 'translateY(-100vh) rotate(720deg)', opacity: '0' }
        },
        
        // Keyframes scale e rotate
        scaleIn: {
          '0%': { transform: 'scale(0)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' }
        },
        scaleOut: {
          '0%': { transform: 'scale(1)', opacity: '1' },
          '100%': { transform: 'scale(0)', opacity: '0' }
        },
        rotateSlow: {
          '0%': { transform: 'rotate(0deg)' },
          '100%': { transform: 'rotate(360deg)' }
        },
        rotateFast: {
          '0%': { transform: 'rotate(0deg)' },
          '100%': { transform: 'rotate(360deg)' }
        },
        
        // Keyframes glow
        glow: {
          '0%': { 'box-shadow': '0 0 5px rgba(59, 130, 246, 0.5)' },
          '100%': { 'box-shadow': '0 0 20px rgba(59, 130, 246, 0.8)' }
        },
        glowPulse: {
          '0%, 100%': { 'box-shadow': '0 0 5px rgba(59, 130, 246, 0.5)' },
          '50%': { 'box-shadow': '0 0 30px rgba(59, 130, 246, 0.8)' }
        },
        
        // Keyframes shimmer
        shimmer: {
          '0%': { transform: 'translateX(-100%)' },
          '100%': { transform: 'translateX(100%)' }
        },
        
        // Keyframes morphing
        morph: {
          '0%, 100%': { 'border-radius': '20px' },
          '25%': { 'border-radius': '50% 20px' },
          '50%': { 'border-radius': '50%' },
          '75%': { 'border-radius': '20px 50%' }
        },
        wave: {
          '0%, 100%': { transform: 'scaleY(1)' },
          '50%': { transform: 'scaleY(1.1)' }
        },
        
        // Keyframes loading
        skeleton: {
          '0%': { 'background-position': '-200% 0' },
          '100%': { 'background-position': '200% 0' }
        },
        
        // Keyframes hover
        hoverFloat: {
          '0%': { transform: 'translateY(0)' },
          '100%': { transform: 'translateY(-5px)' }
        },
        hoverGlow: {
          '0%': { 'box-shadow': '0 0 0 rgba(59, 130, 246, 0)' },
          '100%': { 'box-shadow': '0 0 20px rgba(59, 130, 246, 0.5)' }
        },
        hoverScale: {
          '0%': { transform: 'scale(1)' },
          '100%': { transform: 'scale(1.05)' }
        }
      },
      backgroundImage: {
        'hero-gradient': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'tech-gradient': 'linear-gradient(90deg, #667eea 0%, #764ba2 100%)',
        'mesh-gradient': 'linear-gradient(to right, #3b82f6, #8b5cf6, #ec4899)',
        'glass-gradient': 'linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05))',
        'dark-glass-gradient': 'linear-gradient(135deg, rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.2))',
        'rainbow': 'linear-gradient(90deg, #ff0000, #ff8000, #ffff00, #80ff00, #00ff00, #00ff80, #00ffff, #0080ff, #0000ff, #8000ff, #ff00ff, #ff0080)',
        'sunset': 'linear-gradient(135deg, #ff6b6b, #ffa500, #ff69b4)',
        'ocean': 'linear-gradient(135deg, #667eea, #764ba2, #f093fb)',
        'forest': 'linear-gradient(135deg, #134e5e, #71b280)',
        'cosmic': 'linear-gradient(135deg, #667eea, #764ba2, #f093fb, #f5576c)',
        'aurora': 'linear-gradient(135deg, #a8edea, #fed6e3, #ddd6fe, #b794f6)'
      },
      backdropBlur: {
        'xs': '2px',
        '4xl': '72px'
      },
      backdropSaturate: {
        '25': '.25',
        '75': '.75',
        '125': '1.25',
        '150': '1.5',
        '200': '2'
      },
      boxShadow: {
        'glass': '0 8px 32px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.2)',
        'dark-glass': '0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(148, 163, 184, 0.1)',
        'glow-sm': '0 0 5px rgba(59, 130, 246, 0.5)',
        'glow': '0 0 10px rgba(59, 130, 246, 0.5)',
        'glow-lg': '0 0 20px rgba(59, 130, 246, 0.5)',
        'glow-xl': '0 0 30px rgba(59, 130, 246, 0.5)',
        'premium': '0 25px 50px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.1)',
        'premium-dark': '0 25px 50px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(148, 163, 184, 0.1)'
      },
      borderRadius: {
        '4xl': '2rem',
        '5xl': '2.5rem',
        '6xl': '3rem'
      },
      zIndex: {
        '60': '60',
        '70': '70',
        '80': '80',
        '90': '90',
        '100': '100'
      },
      scale: {
        '25': '.25',
        '30': '.3',
        '35': '.35',
        '65': '.65',
        '70': '.7',
        '80': '.8',
        '85': '.85',
        '115': '1.15',
        '120': '1.2',
        '125': '1.25'
      },
      transitionDuration: {
        '0': '0ms',
        '2000': '2000ms',
        '3000': '3000ms',
        '4000': '4000ms',
        '5000': '5000ms'
      },
      transitionTimingFunction: {
        'bounce-in': 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
        'smooth': 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
        'premium': 'cubic-bezier(0.175, 0.885, 0.32, 1.275)'
      }
    }
  },
  plugins: [
    // Plugin per utilities personalizzate
    function({ addUtilities }) {
      const newUtilities = {
        '.text-shadow': {
          'text-shadow': '0 2px 4px rgba(0, 0, 0, 0.1)'
        },
        '.text-shadow-lg': {
          'text-shadow': '0 4px 8px rgba(0, 0, 0, 0.2)'
        },
        '.backdrop-blur-premium': {
          'backdrop-filter': 'blur(20px) saturate(180%)',
          '-webkit-backdrop-filter': 'blur(20px) saturate(180%)'
        },
        '.glass': {
          'background': 'rgba(255, 255, 255, 0.1)',
          'backdrop-filter': 'blur(20px)',
          '-webkit-backdrop-filter': 'blur(20px)',
          'border': '1px solid rgba(255, 255, 255, 0.2)',
          'box-shadow': '0 8px 32px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.2)'
        },
        '.glass-dark': {
          'background': 'rgba(15, 23, 42, 0.4)',
          'backdrop-filter': 'blur(20px)',
          '-webkit-backdrop-filter': 'blur(20px)',
          'border': '1px solid rgba(148, 163, 184, 0.1)',
          'box-shadow': '0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(148, 163, 184, 0.1)'
        }
      }
      
      addUtilities(newUtilities)
    }
  ]
} 