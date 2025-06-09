/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/**/*.{php,html,js}',
    './resources/**/*.{php,html,js}',
    './views/**/*.{php,html,js}',
    './public/**/*.{js,html}',
  ],
  safelist: [
    // Colores azules
    'bg-blue-200',
    'bg-blue-400', 
    'bg-blue-600',
    'bg-blue-700',
    'text-blue-600',
    'text-blue-800',
    'hover:bg-blue-700',
    
    // Colores verdes  
    'bg-green-500',
    'bg-green-600',
    'bg-green-700',
    'text-green-400',
    'hover:bg-green-700',
    
    // Colores rojos
    'bg-red-400',
    'bg-red-600',
    
    // Grises
    'bg-gray-50',
    'bg-gray-100',
    'bg-gray-300',
    'text-gray-400',
    'text-gray-500',
    'text-gray-600',
    'text-gray-700',
    'text-gray-800',
    'border-gray-300',
    
    // Estados
    'disabled:opacity-50',
    'cursor-not-allowed',
    'opacity-50',
  ],

  theme: {
    extend: {},
  },
  plugins: [],
}
