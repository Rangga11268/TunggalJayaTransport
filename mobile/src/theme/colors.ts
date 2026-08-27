export const COLORS = {
  // Deep Obsidian Dark Palette
  bgDark: '#0A0C10',
  bgSurface: '#12151B',
  bgCard: '#161A22',
  bgCardHover: '#1C212C',
  bgPill: '#1B1F27',
  bgElevated: '#202531',

  // Brand Red Accents (Matching RedBus Luxury Palette)
  brandRed: '#FF1A35',
  brandRedDark: '#D6001F',
  brandRedGlow: 'rgba(255, 26, 53, 0.35)',
  brandRedLight: 'rgba(255, 26, 53, 0.15)',

  // Text Hierarchy
  textPrimary: '#FFFFFF',
  textSecondary: '#9AA0AC',
  textMuted: '#5C6370',
  textDisabled: '#3B4048',

  // Accents & Badges
  accentGold: '#FFB800',
  accentGreen: '#00D664',
  accentBlue: '#2D81FF',

  // Subtle Borders
  borderLight: 'rgba(255, 255, 255, 0.08)',
  borderMedium: 'rgba(255, 255, 255, 0.14)',
  borderRed: 'rgba(255, 26, 53, 0.45)',

  // Overlays
  overlayDark: 'rgba(10, 12, 16, 0.85)',
  glassBg: 'rgba(22, 26, 34, 0.82)',
};

export const GRADIENTS = {
  redPrimary: ['#FF1A35', '#D6001F'] as const,
  heroOverlay: ['transparent', 'rgba(10, 12, 16, 0.6)', '#0A0C10'] as const,
  cardGlow: ['#1E232E', '#14171E'] as const,
  darkSheet: ['rgba(18, 21, 27, 0.95)', '#0A0C10'] as const,
};

// Comprehensive token mapping for 100% type-safety & backwards compatibility
export const Colors = {
  background: COLORS.bgDark,
  card: COLORS.bgCard,
  surface: COLORS.bgSurface,
  surfaceCard: '#161A22',
  surfaceContainer: '#12151B',
  surfaceElevated: '#202531',
  surfaceHighest: '#1F2430',
  primary: COLORS.brandRed,
  primaryLight: 'rgba(255, 26, 53, 0.15)',
  primaryContainer: 'rgba(255, 26, 53, 0.15)',
  primaryGlow: 'rgba(255, 26, 53, 0.35)',
  secondary: COLORS.accentGold,
  text: COLORS.textPrimary,
  textPrimary: COLORS.textPrimary,
  textSecondary: COLORS.textSecondary,
  textMuted: COLORS.textMuted,
  textLight: COLORS.textMuted,
  border: COLORS.borderLight,
  borderLight: COLORS.borderLight,
  borderMedium: COLORS.borderMedium,
  borderActive: COLORS.borderRed,
  success: COLORS.accentGreen,
  successContainer: 'rgba(0, 214, 100, 0.15)',
  warning: COLORS.accentGold,
  warningContainer: 'rgba(255, 184, 0, 0.15)',
  error: COLORS.brandRed,
  errorContainer: 'rgba(255, 26, 53, 0.15)',
  info: COLORS.accentBlue,
  infoContainer: 'rgba(45, 129, 255, 0.15)',
  gold: COLORS.accentGold,
  seatAvailable: '#1C212B',
  seatSelected: COLORS.brandRed,
  seatOccupied: '#2A2E38',
  seatDisabled: '#161922',
};

export const Radius = {
  sm: 8,
  md: 14,
  lg: 18,
  xl: 24,
  pill: 9999,
  full: 9999,
};
