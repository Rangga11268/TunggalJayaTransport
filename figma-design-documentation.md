# Figma Design Documentation - Home Section

## Overview
This document provides comprehensive design specifications for implementing the Tunggal Jaya Transport home section in Figma. It includes all necessary colors, fonts, icons, assets, and component specifications.

## Color Palette

### Primary Colors
- **Primary Blue**: `#1d4ed8` (Dark blue for primary buttons, headers)
- **Primary Indigo**: `#4f46e5` (Secondary blue-indigo gradient)
- **Blue-700**: `#1d4ed8` (Used in header gradient)
- **Indigo-800**: `#3730a3` (Dark indigo for footer)

### Gradient Colors
- **Header Gradient**: `linear-gradient(to right, #1d4ed8, #4f46e5)` (from blue-700 to indigo-800)
- **Hero Section Gradient**: `linear-gradient(to right, #1e3a8a, #4f46e5)` (from blue-900 to indigo-700)
- **Hero Overlay**: `rgba(30, 58, 138, 0.6)` (Blue-900 with 60% opacity)
- **Button Primary Gradient**: `linear-gradient(to right, #2563eb, #4338ca)` (from blue-600 to indigo-700)
- **Button Hover Gradient**: `linear-gradient(to right, #1d4ed8, #3730a3)` (from blue-700 to indigo-800)

### Secondary Colors
- **Light Blue**: `#dbeafe` (Background elements)
- **Blue-50**: `#eff6ff` (Lightest blue background)
- **Blue-100**: `#dbeafe`
- **Blue-200**: `#bfdbfe`
- **Blue-400**: `#60a5fa`
- **Blue-500**: `#3b82f6`
- **Blue-600**: `#2563eb`
- **Blue-700**: `#1d4ed8`
- **Blue-800**: `#1e40af`
- **Blue-900**: `#1e3a8a`

### Indigo Colors
- **Indigo-50**: `#eef2ff`
- **Indigo-100**: `#e0e7ff`
- **Indigo-200**: `#c7d2fe`
- **Indigo-300**: `#a5b4fc`
- **Indigo-400**: `#818cf8`
- **Indigo-500**: `#6366f1`
- **Indigo-600**: `#4f46e5`
- **Indigo-700**: `#4338ca`
- **Indigo-800**: `#3730a3`
- **Indigo-900**: `#312e81`

### Green Colors (for success indicators)
- **Green-50**: `#f0fdf4`
- **Green-100**: `#dcfce7`
- **Green-200**: `#bbf7d0`
- **Green-400**: `#4ade80`
- **Green-500**: `#22c55e`
- **Green-600**: `#16a34a`
- **Green-700**: `#15803d`

### Text Colors
- **White**: `#ffffff` (For text on dark backgrounds)
- **Gray-800**: `#1f2937`
- **Gray-700**: `#374151`
- **Gray-600**: `#4b5563`
- **Gray-500**: `#6b7280`
- **Gray-400**: `#9ca3af`
- **Gray-300**: `#d1d5db`

### Status Colors
- **Success**: `#10b981` (Green for success states)
- **Warning**: `#f59e0b` (Yellow for limited availability)
- **Danger**: `#ef4444` (Red for full availability)
- **Info**: `#3b82f6` (Blue for info states)

## Typography

### Primary Font
- **Font Family**: `Figtree` (Primary font defined in tailwind.config.js)
- **Fallback**: `ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"`

### Font Sizes & Hierarchy
- **Hero Title (H1)**: `text-2xl sm:text-3xl md:text-4xl lg:text-5xl` (28px-40px)
- **Section Titles (H2)**: `text-2xl sm:text-3xl` (24px-30px)
- **Card Titles (H3)**: `text-base sm:text-xl` (16px-20px)
- **Body Text**: `text-base` (16px)
- **Small Text**: `text-sm` (14px)
- **Extra Small Text**: `text-xs` (12px)

### Font Weights
- **Light**: `font-light` (300)
- **Normal**: `font-normal` (400)
- **Medium**: `font-medium` (500)
- **Semi Bold**: `font-semibold` (600)
- **Bold**: `font-bold` (700)

### Line Height
- **Tight**: `leading-tight` (1.25)
- **Normal**: `leading-normal` (1.5)
- **Relaxed**: `leading-relaxed` (1.625)

## Icon System

### Icon Library
- **FontAwesome 5/6** (fas, far, fab classes)

### Common Icons Used
- **fas fa-ticket-alt** - Booking tickets
- **fas fa-bus** - Bus transportation
- **fas fa-arrow-right** - Navigation arrows
- **fas fa-road** - Distance/route info
- **fas fa-couch** - Comfortable seats
- **fas fa-clock** - Time/punctuality
- **fas fa-user-friends** - Friendly staff
- **fas fa-shield-alt** - Safety
- **fas fa-spinner fa-spin** - Loading indicators
- **fas fa-check-circle** - Available status
- **fas fa-exclamation-circle** - Limited status
- **fas fa-times-circle** - Full status
- **fas fa-chevron-right** - Navigation
- **fas fa-search** - Search functionality
- **far fa-calendar** - Date information
- **fab fa-facebook-f** - Social media
- **fab fa-twitter** - Social media
- **fab fa-instagram** - Social media
- **fab fa-linkedin-in** - Social media

## Components Specifications

### Hero Section
- **Background**: Gradient from blue-900 to indigo-900 with video/image overlay
- **Height**: Responsive with padding (py-12 sm:py-16 md:py-24 lg:py-32)
- **Content Alignment**: Centered on mobile, left-aligned on medium screens
- **Text Container**: With background blur effect (backdrop-blur-sm) and opacity
- **Buttons**: 
  - Primary: Gradient button with hover scale effect
  - Secondary: Transparent with border, hover background change
- **CTA Buttons**: 
  - "Book Now" - Gradient primary button with ticket icon
  - "View Fleet" - Border button with bus icon

### Quick Booking Form
- **Container**: Light blue gradient background (from-blue-50 to-indigo-50)
- **Layout**: Responsive grid 1 column on mobile, 5 columns on desktop
- **Input Fields**: Rounded borders with focus effects
- **Date Input**: Standard date picker
- **Bus Type Selector**: Dropdown with options
- **Search Button**: Gradient primary button with search icon
- **Availability Indicator**: Real-time seat availability display with status colors

### Featured Routes Section
- **Card Layout**: 1 column on mobile, 3 columns on desktop
- **Card Styling**: White background, shadow, hover scale effect
- **Card Header**: Blue-indigo gradient line at top
- **Route Display**: Origin and destination with arrow separator
- **Distance**: Displayed with road icon
- **View Details Link**: With arrow icon

### Company Highlights Section
- **Background**: Blue-50 to indigo-50 gradient
- **Card Layout**: 1 column on mobile, 4 columns on desktop
- **Icon Circles**: Blue-100 background with blue-600 icons
- **Card Styling**: White background with shadow, hover effect

### News Section
- **Layout**: 1 column on mobile, 3 columns on desktop
- **Image Placeholder**: Gradient background with image icon when no image
- **Card Styling**: White background with shadow
- **Date Display**: With calendar icon
- **Categories**: Tag styling with background colors

### Statistics Counter
- **Background**: Blue-50 to indigo-50 gradient
- **Animation**: Count-up animation when in viewport
- **Card Layout**: 3 equally spaced columns
- **Number Styling**: Large font with blue-600 color

## Spacing System
- **Spacing Unit**: `0.25rem` (4px) base unit
- **Common Paddings**: `p-4`, `p-6`, `p-8`
- **Common Margins**: `m-4`, `m-6`, `m-8`, `mb-4`, `mb-6`, `mb-8`
- **Responsive Spacing**: Increases with screen size (sm, md, lg)

## Shadows & Elevation
- **Shadow-sm**: `0 1px 2px 0 rgba(0, 0, 0, 0.05)`
- **Shadow-md**: `0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)`
- **Shadow-lg**: `0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)`
- **Shadow-xl**: `0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05)`

## Animations & Transitions
- **Fade In Down**: Custom animation in CSS
- **Fade In Up**: Custom animation in CSS
- **Pulse Animation**: For favorite route cards
- **Hover Scale**: `hover:scale-105` for buttons and cards
- **Transition Duration**: `duration-300` (300ms) for most elements
- **Transition Function**: `ease-in-out`

## Header & Navigation
- **Background**: Gradient from blue-700 to indigo-800
- **Backdrop Filter**: `backdrop-filter: blur(12px)` for glass effect
- **Logo**: With transparent background
- **Navigation Links**: White text with hover effects
- **Dropdown**: With backdrop filter and smooth transitions

## Footer
- **Background**: Gradient from gray-900 to blue-900
- **Content Layout**: 4 columns on large screens, 2 on medium, 1 on mobile
- **Social Icons**: Circular background with hover effects
- **Quick Links**: With chevron icons
- **Contact Info**: With appropriate icons

## Assets Required

### Images
- **heroImg.jpg** - Main hero background image
- **logoNoBg.png** - Logo with transparent background
- **car-seat.png** - Seat icon for availability indicator

### Videos
- **cinematic 1.mp4** - Hero video background

### Placeholder Assets for Figma
- **Bus icon** - 512x512px, blue color
- **Seat icon** - 32x32px, blue color
- **Map/Route icon** - 32x32px, blue color
- **Calendar icon** - 32x32px, blue color
- **User icon** - 32x32px, blue color
- **Phone icon** - 32x32px, blue color
- **Email icon** - 32x32px, blue color
- **Location icon** - 32x32px, blue color
- **Clock icon** - 32x32px, blue color
- **Shield/Security icon** - 32x32px, blue color
- **Social media icons** - Facebook, Twitter, Instagram, LinkedIn

## Responsive Breakpoints
- **Mobile**: 320px - 639px
- **Small Tablet**: 640px - 767px
- **Large Tablet**: 768px - 1023px
- **Desktop**: 1024px+

## Design Tokens (Figma Variables)
1. **Primary Color**: #1d4ed8
2. **Secondary Color**: #4f46e5
3. **Success Color**: #10b981
4. **Warning Color**: #f59e0b
5. **Danger Color**: #ef4444
6. **Text Primary**: #1f2937
7. **Text Secondary**: #6b7280
8. **Background Primary**: #ffffff
9. **Background Secondary**: #f9fafb
10. **Gradient Primary**: linear-gradient(90deg, #1d4ed8 0%, #4f46e5 100%)

## Component States
- **Default**: Normal state
- **Hover**: Slightly darker or more saturated colors
- **Active**: Pressed state with scale transform
- **Focus**: With ring border for accessibility
- **Disabled**: Reduced opacity (0.5) for interactive elements

## Accessibility Considerations
- **Contrast Ratio**: Ensure minimum 4.5:1 for normal text, 3:1 for large text
- **Focus States**: Visible focus rings for keyboard navigation
- **Icon Labels**: Accompanied by text for screen readers
- **Alternative Text**: For all images and videos