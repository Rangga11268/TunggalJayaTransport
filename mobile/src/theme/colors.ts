export const COLORS = {
  // Warm Alabaster Luxury Light Palette
  bgDark: '#F4F6F9',        // Soft Alabaster Mist Canvas (not harsh white)
  bgSurface: '#FFFFFF',     // Pure Card Surface for clean elevation
  bgCard: '#FFFFFF',        // Card Container
  bgCardHover: '#F8FAFC',   // Subtle Card Hover
  bgPill: '#EEF2F6',        // Muted Pill / Input Background
  bgElevated: '#FFFFFF',    // Elevated modals / popups
  bgInput: '#F1F4F8',       // Form input background

  // Brand Red Accents (Tunggal Jaya / RedBus Signature Crimson)
  brandRed: '#E60023',
  brandRedDark: '#C4001E',
  brandRedGlow: 'rgba(230, 0, 35, 0.22)',
  brandRedLight: 'rgba(230, 0, 35, 0.08)',

  // Text Hierarchy (Soft Charcoal for zero eye-strain)
  textPrimary: '#111827',   // Deep Obsidian Charcoal
  textSecondary: '#4B5563', // Slate Gray
  textMuted: '#9CA3AF',     // Subtle placeholder gray
  textDisabled: '#D1D5DB',  // Disabled text

  // Accents & Badges
  accentGold: '#D97706',
  accentGreen: '#059669',
  accentBlue: '#2563EB',

  // Crisp Hairline Borders
  borderLight: '#E2E8F0',
  borderMedium: '#CBD5E1',
  borderRed: 'rgba(230, 0, 35, 0.45)',

  // Shadows & Overlays
  shadowSoft: 'rgba(0, 0, 0, 0.05)',
  shadowMedium: 'rgba(0, 0, 0, 0.1)',
  overlayDark: 'rgba(17, 24, 39, 0.65)',
  glassBg: 'rgba(255, 255, 255, 0.9)',
};

export const GRADIENTS = {
  redPrimary: ['#FF1A35', '#E60023'] as const,
  heroOverlay: ['transparent', 'rgba(244, 246, 249, 0.6)', '#F4F6F9'] as const,
  cardGlow: ['#FFFFFF', '#F8FAFC'] as const,
  darkSheet: ['rgba(255, 255, 255, 0.95)', '#F4F6F9'] as const,
};

// Full token mapping for backward compatibility and type-safety
export const Colors = {
  background: COLORS.bgDark,
  card: COLORS.bgCard,
  surface: COLORS.bgSurface,
  surfaceCard: COLORS.bgCard,
  surfaceContainer: COLORS.bgPill,
  surfaceElevated: COLORS.bgElevated,
  surfaceHighest: '#E2E8F0',
  primary: COLORS.brandRed,
  primaryLight: COLORS.brandRedLight,
  primaryContainer: COLORS.brandRedLight,
  primaryGlow: COLORS.brandRedGlow,
  secondary: COLORS.accentGold,
  text: COLORS.textPrimary,
  textPrimary: COLORS.textPrimary,
  textSecondary: COLORS.textSecondary,
  textMuted: COLORS.textSecondary,
  textLight: COLORS.textMuted,
  border: COLORS.borderLight,
  borderLight: COLORS.borderLight,
  borderMedium: COLORS.borderMedium,
  borderActive: COLORS.borderRed,
  success: COLORS.accentGreen,
  successContainer: 'rgba(5, 150, 105, 0.12)',
  warning: COLORS.accentGold,
  warningContainer: 'rgba(217, 119, 6, 0.12)',
  error: COLORS.brandRed,
  errorContainer: COLORS.brandRedLight,
  info: COLORS.accentBlue,
  infoContainer: 'rgba(37, 99, 235, 0.12)',
  gold: COLORS.accentGold,
  seatAvailable: '#FFFFFF',
  seatSelected: COLORS.brandRed,
  seatOccupied: '#E2E8F0',
  seatDisabled: '#F1F4F8',
};

export const Radius = {
  sm: 8,
  md: 14,
  lg: 18,
  xl: 24,
  pill: 9999,
  full: 9999,
};
