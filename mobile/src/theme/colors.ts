export const COLORS = {
  // Warm Alabaster Luxury Light Palette
  bgDark: '#F4F6F9',        // Soft Alabaster Mist Canvas (not harsh white)
  bgSurface: '#FFFFFF',     // Pure Card Surface for clean elevation
  bgCard: '#FFFFFF',        // Card Container
  bgCardHover: '#F8FAFC',   // Subtle Card Hover
  bgPill: '#EEF2F6',        // Muted Pill / Input Background
  bgElevated: '#FFFFFF',    // Elevated modals / popups
  bgInput: '#F1F4F8',       // Form input background

  // Modern Royal Blue Brand Accents
  brandBlue: '#2563EB',
  brandBlueDark: '#1D4ED8',
  brandBlueLight: 'rgba(37, 99, 235, 0.08)',
  brandBlueGlow: 'rgba(37, 99, 235, 0.22)',

  // Legacy alias pointing to Blue for 100% theme consistency
  brandRed: '#2563EB',
  brandRedDark: '#1D4ED8',
  brandRedGlow: 'rgba(37, 99, 235, 0.22)',
  brandRedLight: 'rgba(37, 99, 235, 0.08)',

  // High-Contrast Text Hierarchy (Zero White-on-White Bugs)
  textPrimary: '#111827',   // Deep Obsidian Charcoal
  textSecondary: '#4B5563', // Slate Gray
  textMuted: '#6B7280',     // Readable muted gray
  textDisabled: '#9CA3AF',  // Disabled text

  // Accents & Badges
  accentGold: '#D97706',
  accentGreen: '#059669',
  accentBlue: '#2563EB',
  accentCyan: '#0284C7',

  // Crisp Hairline Borders
  borderLight: '#E2E8F0',
  borderMedium: '#CBD5E1',
  borderBlue: 'rgba(37, 99, 235, 0.45)',
  borderRed: 'rgba(37, 99, 235, 0.45)',

  // Shadows & Overlays
  shadowSoft: 'rgba(0, 0, 0, 0.05)',
  shadowMedium: 'rgba(0, 0, 0, 0.1)',
  overlayDark: 'rgba(17, 24, 39, 0.65)',
  glassBg: 'rgba(255, 255, 255, 0.95)',
};

export const GRADIENTS = {
  bluePrimary: ['#3B82F6', '#1D4ED8'] as const,
  redPrimary: ['#3B82F6', '#1D4ED8'] as const,
  heroOverlay: ['transparent', 'rgba(244, 246, 249, 0.6)', '#F4F6F9'] as const,
  cardGlow: ['#FFFFFF', '#F8FAFC'] as const,
  darkSheet: ['rgba(255, 255, 255, 0.98)', '#F4F6F9'] as const,
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
  primary: COLORS.brandBlue,
  primaryLight: COLORS.brandBlueLight,
  primaryContainer: COLORS.brandBlueLight,
  primaryGlow: COLORS.brandBlueGlow,
  secondary: COLORS.accentGold,
  accent: COLORS.accentCyan,
  success: COLORS.accentGreen,
  warning: COLORS.accentGold,
  error: '#DC2626',
  border: COLORS.borderLight,
  borderLight: COLORS.borderLight,
  borderActive: COLORS.brandBlue,
  text: COLORS.textPrimary,
  textSecondary: COLORS.textSecondary,
  textMuted: COLORS.textMuted,
  textDisabled: COLORS.textDisabled,
  iconDefault: '#4B5563',
  iconActive: COLORS.brandBlue,
  badgeBg: COLORS.brandBlueLight,
  badgeText: COLORS.brandBlue,
};

export const Radius = {
  sm: 8,
  md: 12,
  lg: 16,
  xl: 20,
  xxl: 24,
  pill: 9999,
  full: 9999,
};
