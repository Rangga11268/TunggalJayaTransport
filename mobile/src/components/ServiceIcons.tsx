import React from "react";
import Svg, {
  Defs,
  LinearGradient,
  Stop,
  Rect,
  Circle,
  Path,
  G,
} from "react-native-svg";

interface IconProps {
  size?: number;
}

/**
 * 1. Rich Vector SVG for Tiket Bus AKAP
 * Luxury modern express coach bus with gradient lighting and sleek windshield
 */
export const AkapBusIcon: React.FC<IconProps> = ({ size = 52 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 64 64" fill="none">
      <Defs>
        <LinearGradient id="busBodyGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#3B82F6" />
          <Stop offset="100%" stopColor="#1D4ED8" />
        </LinearGradient>
        <LinearGradient id="glassGrad" x1="0%" y1="0%" x2="0%" y2="100%">
          <Stop offset="0%" stopColor="#93C5FD" stopOpacity="0.9" />
          <Stop offset="100%" stopColor="#60A5FA" stopOpacity="0.4" />
        </LinearGradient>
        <LinearGradient id="glowGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#60A5FA" stopOpacity="0.25" />
          <Stop offset="100%" stopColor="#2563EB" stopOpacity="0.05" />
        </LinearGradient>
      </Defs>

      {/* Soft Background Radial Circle */}
      <Circle cx="32" cy="32" r="30" fill="url(#glowGrad)" />

      {/* Shadow Base */}
      <Rect
        x="14"
        y="52"
        width="36"
        height="4"
        rx="2"
        fill="#CBD5E1"
        opacity="0.8"
      />

      {/* Bus Body */}
      <Rect
        x="14"
        y="10"
        width="36"
        height="40"
        rx="9"
        fill="url(#busBodyGrad)"
      />

      {/* Bus Windshield Top (SHD Single Glass) */}
      <Rect
        x="18"
        y="15"
        width="28"
        height="15"
        rx="5"
        fill="url(#glassGrad)"
      />

      {/* Windshield Wiper Accent / Divider */}
      <Path
        d="M19 30 L45 30"
        stroke="#1E40AF"
        strokeWidth="1.5"
        strokeLinecap="round"
      />

      {/* Destination Display Route Board */}
      <Rect x="23" y="17" width="18" height="4" rx="2" fill="#1E293B" />
      <Rect x="25" y="18.5" width="14" height="1.2" rx="0.6" fill="#38BDF8" />

      {/* Headlights LED Glow */}
      <Rect x="17" y="37" width="7" height="4" rx="2" fill="#FDE047" />
      <Rect x="40" y="37" width="7" height="4" rx="2" fill="#FDE047" />

      {/* Front Radiator Grill */}
      <Rect x="26" y="36" width="12" height="6" rx="2" fill="#1E293B" />
      <Path
        d="M28 39 H36"
        stroke="#94A3B8"
        strokeWidth="1"
        strokeLinecap="round"
      />

      {/* Bumper Guard */}
      <Path
        d="M14 44 H50 V47 C50 48.6 48.6 50 47 50 H17 C15.4 50 14 48.6 14 47 Z"
        fill="#1E3A8A"
      />

      {/* License Plate */}
      <Rect x="28" y="44" width="8" height="3" rx="1" fill="#FFFFFF" />

      {/* Left & Right Wheels */}
      <Rect x="11" y="40" width="4" height="10" rx="2" fill="#0F172A" />
      <Rect x="49" y="40" width="4" height="10" rx="2" fill="#0F172A" />

      {/* Side Mirrors */}
      <Path d="M14 18 L10 20 V25 H14" fill="#2563EB" />
      <Path d="M50 18 L54 20 V25 H50" fill="#2563EB" />
    </Svg>
  );
};

/**
 * 2. Rich Vector SVG for Sewa Pariwisata
 * Tourism & Vacation Globe Compass with Sun & Mountain Horizon
 */
export const CharterPariwisataIcon: React.FC<IconProps> = ({ size = 52 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 64 64" fill="none">
      <Defs>
        <LinearGradient id="parwisBg" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#0284C7" stopOpacity="0.18" />
          <Stop offset="100%" stopColor="#0369A1" stopOpacity="0.05" />
        </LinearGradient>
        <LinearGradient id="compassRing" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#0EA5E9" />
          <Stop offset="100%" stopColor="#0284C7" />
        </LinearGradient>
        <LinearGradient id="needleRed" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#F59E0B" />
          <Stop offset="100%" stopColor="#D97706" />
        </LinearGradient>
        <LinearGradient id="needleBlue" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#64748B" />
          <Stop offset="100%" stopColor="#334155" />
        </LinearGradient>
        <LinearGradient id="mountainGrad" x1="0%" y1="0%" x2="0%" y2="100%">
          <Stop offset="0%" stopColor="#38BDF8" />
          <Stop offset="100%" stopColor="#0284C7" />
        </LinearGradient>
      </Defs>

      {/* Backdrop Glow Circle */}
      <Circle cx="32" cy="32" r="30" fill="url(#parwisBg)" />

      {/* Outer Compass Golden-Cyan Dial */}
      <Circle
        cx="32"
        cy="32"
        r="23"
        fill="#FFFFFF"
        stroke="url(#compassRing)"
        strokeWidth="3"
      />
      <Circle cx="32" cy="32" r="19" fill="#F0F9FF" />

      {/* Compass Tick Markers */}
      <Path
        d="M32 15 V17 M32 47 V49 M15 32 H17 M47 32 H49"
        stroke="#0284C7"
        strokeWidth="2"
        strokeLinecap="round"
      />

      {/* Mountain Landscape Background inside Compass */}
      <Path
        d="M19 37 L26 28 L32 35 L38 26 L45 37 Z"
        fill="url(#mountainGrad)"
        opacity="0.45"
      />

      {/* Bright Golden Sun */}
      <Circle cx="24" cy="24" r="3.5" fill="#FBBF24" />

      {/* North Compass Needle (Vibrant Amber/Gold) */}
      <Path d="M32 32 L28.5 31 L32 18 L35.5 31 Z" fill="url(#needleRed)" />

      {/* South Compass Needle (Steel Slate) */}
      <Path d="M32 32 L28.5 33 L32 46 L35.5 33 Z" fill="url(#needleBlue)" />

      {/* Center Pivot Pin */}
      <Circle
        cx="32"
        cy="32"
        r="3.5"
        fill="#FFFFFF"
        stroke="#0284C7"
        strokeWidth="2"
      />
      <Circle cx="32" cy="32" r="1.5" fill="#0284C7" />
    </Svg>
  );
};

/**
 * 3. Rich Vector SVG for Riwayat Pesanan
 * Emerald Green Digital Ticket Boarding Pass with QR & Checkmark
 */
export const BookingHistoryIcon: React.FC<IconProps> = ({ size = 52 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 64 64" fill="none">
      <Defs>
        <LinearGradient id="historyBg" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#10B981" stopOpacity="0.18" />
          <Stop offset="100%" stopColor="#059669" stopOpacity="0.05" />
        </LinearGradient>
        <LinearGradient id="ticketBody" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#FFFFFF" />
          <Stop offset="100%" stopColor="#ECFDF5" />
        </LinearGradient>
        <LinearGradient id="headerGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#10B981" />
          <Stop offset="100%" stopColor="#059669" />
        </LinearGradient>
      </Defs>

      {/* Backdrop Glow */}
      <Circle cx="32" cy="32" r="30" fill="url(#historyBg)" />

      {/* Main Ticket Shape with Perforations */}
      <G>
        {/* Ticket Base Card */}
        <Path
          d="M16 14 C16 11.8 17.8 10 20 10 H44 C46.2 10 48 11.8 48 14 V29 C45.8 29 44 30.8 44 33 C44 35.2 45.8 37 48 37 V50 C48 52.2 46.2 54 44 54 H20 C17.8 54 16 52.2 16 50 V37 C18.2 37 20 35.2 20 33 C20 30.8 18.2 29 16 29 Z"
          fill="url(#ticketBody)"
          stroke="#A7F3D0"
          strokeWidth="1.5"
        />

        {/* Ticket Top Header Bar */}
        <Path
          d="M16.5 14 C16.5 12.1 18.1 10.5 20 10.5 H44 C45.9 10.5 47.5 12.1 47.5 14 V21 H16.5 Z"
          fill="url(#headerGrad)"
        />

        {/* Header Micro Bus Silhouette / Text Line */}
        <Rect
          x="22"
          y="14.5"
          width="20"
          height="2.5"
          rx="1.2"
          fill="#FFFFFF"
          opacity="0.9"
        />

        {/* Dashed Perforation Line */}
        <Path
          d="M21 33 H43"
          stroke="#6EE7B7"
          strokeWidth="1.5"
          strokeDasharray="2 2"
        />

        {/* Content Info Lines */}
        <Rect x="21" y="25" width="14" height="2" rx="1" fill="#047857" />
        <Rect x="38" y="25" width="5" height="2" rx="1" fill="#10B981" />

        {/* Verified Green Check Circle Badge */}
        <Circle cx="26" cy="42" r="5" fill="#10B981" />
        <Path
          d="M24 42 L25.5 43.5 L28.5 40.5"
          stroke="#FFFFFF"
          strokeWidth="1.2"
          strokeLinecap="round"
          strokeLinejoin="round"
        />

        {/* Price & Code Lines */}
        <Rect x="34" y="39" width="9" height="2.5" rx="1" fill="#065F46" />
        <Rect x="34" y="43.5" width="7" height="1.8" rx="0.9" fill="#9CA3AF" />
      </G>
    </Svg>
  );
};

/**
 * 4. Rich Vector SVG for Voucher & Kupon Promo
 * Amber Gold Coupon Ticket with Starburst Badge and Sparkles
 */
export const PromoVoucherIcon: React.FC<IconProps> = ({ size = 52 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 64 64" fill="none">
      <Defs>
        <LinearGradient id="promoBg" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#F59E0B" stopOpacity="0.2" />
          <Stop offset="100%" stopColor="#D97706" stopOpacity="0.05" />
        </LinearGradient>
        <LinearGradient id="tagGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#FBBF24" />
          <Stop offset="100%" stopColor="#D97706" />
        </LinearGradient>
        <LinearGradient id="ribbonGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#EF4444" />
          <Stop offset="100%" stopColor="#B91C1C" />
        </LinearGradient>
      </Defs>

      {/* Backdrop Glow */}
      <Circle cx="32" cy="32" r="30" fill="url(#promoBg)" />

      {/* Slanted Luxury Gift / Discount Tag */}
      <G transform="rotate(-8 32 32)">
        {/* Shadow */}
        <Path
          d="M17 18 C17 15.8 18.8 14 21 14 H35 L49 28 L35 48 L21 48 C18.8 48 17 46.2 17 44 Z"
          fill="#FDE68A"
          opacity="0.6"
          transform="translate(2, 2)"
        />

        {/* Main Tag Body */}
        <Path
          d="M17 18 C17 15.8 18.8 14 21 14 H35 L49 28 L35 48 L21 48 C18.8 48 17 46.2 17 44 Z"
          fill="url(#tagGrad)"
        />

        {/* Tag Hole Grommet */}
        <Circle cx="24" cy="22" r="3.5" fill="#FFFFFF" />
        <Circle cx="24" cy="22" r="2" fill="#B45309" opacity="0.3" />

        {/* Starburst Discount Symbol / % */}
        <Path
          d="M32 25 L34 29 L38 29 L35 32 L36 36 L32 34 L28 36 L29 32 L26 29 L30 29 Z"
          fill="#FFFFFF"
        />

        {/* Coupon Barcode Dashes */}
        <Rect
          x="22"
          y="38"
          width="2"
          height="6"
          rx="0.5"
          fill="#FFFFFF"
          opacity="0.85"
        />
        <Rect
          x="26"
          y="38"
          width="1.5"
          height="6"
          rx="0.5"
          fill="#FFFFFF"
          opacity="0.85"
        />
        <Rect
          x="29.5"
          y="38"
          width="3"
          height="6"
          rx="0.5"
          fill="#FFFFFF"
          opacity="0.85"
        />
        <Rect
          x="34"
          y="38"
          width="1.5"
          height="6"
          rx="0.5"
          fill="#FFFFFF"
          opacity="0.85"
        />
        <Rect
          x="37"
          y="38"
          width="2"
          height="6"
          rx="0.5"
          fill="#FFFFFF"
          opacity="0.85"
        />
      </G>

      {/* Sparkles Accents */}
      <Path
        d="M47 14 L48.5 18 L52.5 19.5 L48.5 21 L47 25 L45.5 21 L41.5 19.5 L45.5 18 Z"
        fill="#F59E0B"
      />
      <Circle cx="15" cy="46" r="2" fill="#FBBF24" />
      <Circle cx="50" cy="48" r="1.5" fill="#F59E0B" />
    </Svg>
  );
};

/**
 * 5. Rich Vector SVG for Suspensi Udara (Air Suspension)
 */
export const AirSuspensionIcon: React.FC<IconProps> = ({ size = 44 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 64 64" fill="none">
      <Defs>
        <LinearGradient id="airGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#38BDF8" />
          <Stop offset="100%" stopColor="#0284C7" />
        </LinearGradient>
        <LinearGradient id="airBg" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#E0F2FE" />
          <Stop offset="100%" stopColor="#BAE6FD" />
        </LinearGradient>
      </Defs>
      <Circle cx="32" cy="32" r="30" fill="url(#airBg)" />
      {/* Top Mounting Plate */}
      <Rect x="20" y="14" width="24" height="5" rx="2.5" fill="#0369A1" />
      {/* Air Bellows / Cushion */}
      <Rect x="16" y="21" width="32" height="9" rx="4.5" fill="url(#airGrad)" />
      <Rect x="16" y="32" width="32" height="9" rx="4.5" fill="url(#airGrad)" />
      {/* Center Connecting Piston */}
      <Rect x="28" y="27" width="8" height="8" fill="#0284C7" />
      {/* Bottom Mounting Plate */}
      <Rect x="20" y="43" width="24" height="5" rx="2.5" fill="#0369A1" />
      {/* Air Wave Ripples */}
      <Path
        d="M10 26 C12 24 14 28 16 26"
        stroke="#38BDF8"
        strokeWidth="2"
        strokeLinecap="round"
      />
      <Path
        d="M48 26 C50 24 52 28 54 26"
        stroke="#38BDF8"
        strokeWidth="2"
        strokeLinecap="round"
      />
      <Path
        d="M10 37 C12 35 14 39 16 37"
        stroke="#38BDF8"
        strokeWidth="2"
        strokeLinecap="round"
      />
      <Path
        d="M48 37 C50 35 52 39 54 37"
        stroke="#38BDF8"
        strokeWidth="2"
        strokeLinecap="round"
      />
    </Svg>
  );
};

/**
 * 6. Rich Vector SVG for Servis Makan Prasmanan (Buffet Meal)
 */
export const FreeMealBuffetIcon: React.FC<IconProps> = ({ size = 44 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 64 64" fill="none">
      <Defs>
        <LinearGradient id="mealGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#10B981" />
          <Stop offset="100%" stopColor="#047857" />
        </LinearGradient>
        <LinearGradient id="mealBg" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#ECFDF5" />
          <Stop offset="100%" stopColor="#D1FAE5" />
        </LinearGradient>
      </Defs>
      <Circle cx="32" cy="32" r="30" fill="url(#mealBg)" />
      {/* Plate Base */}
      <Rect x="12" y="44" width="40" height="5" rx="2.5" fill="#065F46" />
      {/* Cloche Dome Cover */}
      <Path d="M16 41 C16 24 48 24 48 41 Z" fill="url(#mealGrad)" />
      {/* Cloche Handle */}
      <Circle cx="32" cy="20" r="4" fill="#047857" />
      {/* Plate Rim Highlight */}
      <Rect x="14" y="40" width="36" height="2" rx="1" fill="#A7F3D0" />
      {/* Steam Aromas */}
      <Path
        d="M26 14 C25 11 27 9 26 6"
        stroke="#10B981"
        strokeWidth="2"
        strokeLinecap="round"
      />
      <Path
        d="M38 14 C37 11 39 9 38 6"
        stroke="#10B981"
        strokeWidth="2"
        strokeLinecap="round"
      />
    </Svg>
  );
};

/**
 * 7. Rich Vector SVG for USB Fast Charging
 */
export const FastChargingIcon: React.FC<IconProps> = ({ size = 44 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 64 64" fill="none">
      <Defs>
        <LinearGradient id="chargeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#F59E0B" />
          <Stop offset="100%" stopColor="#D97706" />
        </LinearGradient>
        <LinearGradient id="chargeBg" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#FFFBEB" />
          <Stop offset="100%" stopColor="#FEF3C7" />
        </LinearGradient>
      </Defs>
      <Circle cx="32" cy="32" r="30" fill="url(#chargeBg)" />
      {/* Battery / Charger Shield Frame */}
      <Rect x="18" y="16" width="28" height="34" rx="7" fill="#FDE68A" />
      <Rect x="28" y="12" width="8" height="4" rx="2" fill="#D97706" />
      {/* Inner Screen */}
      <Rect x="21" y="19" width="22" height="28" rx="4" fill="#78350F" />
      {/* Dynamic Lightning Bolt */}
      <Path
        d="M34 22 L25 33 H32 L30 44 L39 31 H32 L34 22 Z"
        fill="url(#chargeGrad)"
      />
      {/* Energy Sparks */}
      <Circle cx="46" cy="22" r="2" fill="#F59E0B" />
      <Circle cx="16" cy="42" r="1.5" fill="#F59E0B" />
    </Svg>
  );
};

/**
 * 8. Rich Vector SVG for Kopi & Air Mineral
 */
export const FreeCoffeeIcon: React.FC<IconProps> = ({ size = 44 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 64 64" fill="none">
      <Defs>
        <LinearGradient id="coffeeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#8B5CF6" />
          <Stop offset="100%" stopColor="#6D28D9" />
        </LinearGradient>
        <LinearGradient id="coffeeBg" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#F5F3FF" />
          <Stop offset="100%" stopColor="#EDE9FE" />
        </LinearGradient>
      </Defs>
      <Circle cx="32" cy="32" r="30" fill="url(#coffeeBg)" />
      {/* Saucer Plate */}
      <Rect x="14" y="46" width="36" height="4" rx="2" fill="#5B21B6" />
      {/* Coffee Cup */}
      <Path
        d="M18 24 H42 V38 C42 43 37 46 30 46 C23 46 18 43 18 38 Z"
        fill="url(#coffeeGrad)"
      />
      {/* Cup Handle */}
      <Path
        d="M42 27 H46 C48.5 27 50 28.5 50 31 C50 33.5 48.5 35 46 35 H42"
        stroke="#6D28D9"
        strokeWidth="3.5"
        strokeLinecap="round"
      />
      {/* Hot Steam */}
      <Path
        d="M24 18 C23 15 25 13 24 10"
        stroke="#8B5CF6"
        strokeWidth="2"
        strokeLinecap="round"
      />
      <Path
        d="M30 18 C29 15 31 13 30 10"
        stroke="#8B5CF6"
        strokeWidth="2"
        strokeLinecap="round"
      />
      <Path
        d="M36 18 C35 15 37 13 36 10"
        stroke="#8B5CF6"
        strokeWidth="2"
        strokeLinecap="round"
      />
    </Svg>
  );
};

/**
 * 9. Authentic Official WhatsApp Vector SVG
 */
export const OfficialWhatsAppIcon: React.FC<{
  size?: number;
  color?: string;
  style?: any;
}> = ({ size = 20, color = "#25D366", style }) => {
  return (
    <Svg
      width={size}
      height={size}
      viewBox="0 0 24 24"
      fill="none"
      style={style}
    >
      <Path
        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
        fill={color}
      />
    </Svg>
  );
};

/**
 * 10. Authentic Official Instagram Vector SVG
 */
export const OfficialInstagramIcon: React.FC<{
  size?: number;
  color?: string;
  style?: any;
}> = ({ size = 20, color = "#E1306C", style }) => {
  return (
    <Svg
      width={size}
      height={size}
      viewBox="0 0 24 24"
      fill="none"
      style={style}
    >
      <Path
        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"
        fill={color}
      />
    </Svg>
  );
};

/**
 * 11. Luxury Vector Bus Ticket Navigation Icon
 */
export const NavTicketIcon: React.FC<{
  size?: number;
  color?: string;
  strokeWidth?: number;
}> = ({ size = 20, color = "#64748B", strokeWidth = 2 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 24 24" fill="none">
      {/* Outer Ticket Outline with Notches */}
      <Path
        d="M2 9a3 3 0 0 1 0 6v3a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-3a3 3 0 0 1 0-6V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v3z"
        stroke={color}
        strokeWidth={strokeWidth}
        strokeLinecap="round"
        strokeLinejoin="round"
      />
      {/* Perforated Vertical Line */}
      <Path
        d="M13 5v2m0 3v2m0 3v2m0 3v2"
        stroke={color}
        strokeWidth={strokeWidth}
        strokeLinecap="round"
      />
      {/* Star / Bus glyph on left stub */}
      <Path
        d="M7.5 10l1 2 2 .3-1.5 1.4.4 2-1.9-1-1.9 1 .4-2-1.5-1.4 2-.3 1-2z"
        fill={color}
      />
    </Svg>
  );
};

/**
 * 12. FAQ SVG Vector Icon: Pemesanan Tiket AKAP
 */
export const FaqAkapBookingIcon: React.FC<IconProps> = ({ size = 36 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 48 48" fill="none">
      <Defs>
        <LinearGradient id="faqBusGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#3B82F6" />
          <Stop offset="100%" stopColor="#1D4ED8" />
        </LinearGradient>
      </Defs>
      <Rect width="48" height="48" rx="14" fill="#EFF6FF" />
      {/* Bus Body */}
      <Rect
        x="12"
        y="10"
        width="24"
        height="26"
        rx="6"
        fill="url(#faqBusGrad)"
      />
      {/* Windshield */}
      <Rect x="15" y="13" width="18" height="8" rx="2" fill="#93C5FD" />
      {/* Grille */}
      <Rect
        x="20"
        y="24"
        width="8"
        height="2"
        rx="1"
        fill="#FFFFFF"
        opacity="0.8"
      />
      {/* Headlights */}
      <Circle cx="16" cy="27" r="2" fill="#FEF08A" />
      <Circle cx="32" cy="27" r="2" fill="#FEF08A" />
      {/* Wheels */}
      <Rect x="14" y="34" width="4" height="4" rx="2" fill="#1E293B" />
      <Rect x="30" y="34" width="4" height="4" rx="2" fill="#1E293B" />
    </Svg>
  );
};

/**
 * 13. FAQ SVG Vector Icon: QR Boarding Pass
 */
export const FaqQrBoardingIcon: React.FC<IconProps> = ({ size = 36 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 48 48" fill="none">
      <Defs>
        <LinearGradient id="faqQrGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#2563EB" />
          <Stop offset="100%" stopColor="#1E40AF" />
        </LinearGradient>
      </Defs>
      <Rect width="48" height="48" rx="14" fill="#EFF6FF" />
      {/* QR Outline Frame */}
      <Rect
        x="12"
        y="12"
        width="24"
        height="24"
        rx="4"
        fill="#FFFFFF"
        stroke="#3B82F6"
        strokeWidth="1.5"
      />
      {/* Top Left Corner */}
      <Rect
        x="15"
        y="15"
        width="6"
        height="6"
        rx="1.5"
        fill="url(#faqQrGrad)"
      />
      {/* Top Right Corner */}
      <Rect
        x="27"
        y="15"
        width="6"
        height="6"
        rx="1.5"
        fill="url(#faqQrGrad)"
      />
      {/* Bottom Left Corner */}
      <Rect
        x="15"
        y="27"
        width="6"
        height="6"
        rx="1.5"
        fill="url(#faqQrGrad)"
      />
      {/* Center Data Dots */}
      <Rect x="23" y="23" width="3" height="3" rx="0.5" fill="#3B82F6" />
      <Rect x="28" y="27" width="3" height="3" rx="0.5" fill="#3B82F6" />
      {/* Scanner Laser Beam */}
      <Path
        d="M10 24 H38"
        stroke="#EF4444"
        strokeWidth="1.5"
        strokeDasharray="2 1"
      />
    </Svg>
  );
};

/**
 * 14. FAQ SVG Vector Icon: Sewa Bus Pariwisata
 */
export const FaqCharterIcon: React.FC<IconProps> = ({ size = 36 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 48 48" fill="none">
      <Defs>
        <LinearGradient id="faqGoldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#F59E0B" />
          <Stop offset="100%" stopColor="#D97706" />
        </LinearGradient>
      </Defs>
      <Rect width="48" height="48" rx="14" fill="#FFFBEB" />
      {/* Compass Outer Ring */}
      <Circle
        cx="24"
        cy="24"
        r="13"
        stroke="url(#faqGoldGrad)"
        strokeWidth="2.5"
      />
      {/* Compass North Arrow */}
      <Path d="M24 14 L28 24 L24 22 L20 24 Z" fill="#D97706" />
      {/* Compass South Arrow */}
      <Path d="M24 34 L28 24 L24 22 L20 24 Z" fill="#FDE68A" />
      <Circle cx="24" cy="24" r="2" fill="#78350F" />
    </Svg>
  );
};

/**
 * 15. FAQ SVG Vector Icon: Reschedule & Waktu
 */
export const FaqRescheduleClockIcon: React.FC<IconProps> = ({ size = 36 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 48 48" fill="none">
      <Defs>
        <LinearGradient id="faqClockGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#8B5CF6" />
          <Stop offset="100%" stopColor="#6D28D9" />
        </LinearGradient>
      </Defs>
      <Rect width="48" height="48" rx="14" fill="#F5F3FF" />
      {/* Clock Face */}
      <Circle
        cx="24"
        cy="24"
        r="13"
        fill="#EDE9FE"
        stroke="url(#faqClockGrad)"
        strokeWidth="2"
      />
      {/* Clock Hands */}
      <Path
        d="M24 16 V24 H30"
        stroke="#6D28D9"
        strokeWidth="2.5"
        strokeLinecap="round"
      />
      {/* Reschedule Curved Arrow */}
      <Path
        d="M34 16 A13 13 0 0 0 16 16"
        stroke="#8B5CF6"
        strokeWidth="2"
        strokeLinecap="round"
      />
      <Path d="M14 13 L16 16 L19 14" fill="#8B5CF6" />
    </Svg>
  );
};

/**
 * 16. FAQ SVG Vector Icon: Metode Pembayaran
 */
export const FaqPaymentCardIcon: React.FC<IconProps> = ({ size = 36 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 48 48" fill="none">
      <Defs>
        <LinearGradient id="faqCardGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#10B981" />
          <Stop offset="100%" stopColor="#059669" />
        </LinearGradient>
      </Defs>
      <Rect width="48" height="48" rx="14" fill="#ECFDF5" />
      {/* Card Body */}
      <Rect
        x="10"
        y="14"
        width="28"
        height="20"
        rx="4"
        fill="url(#faqCardGrad)"
      />
      {/* Card Stripe */}
      <Rect x="10" y="19" width="28" height="4" fill="#065F46" />
      {/* EMV Chip */}
      <Rect x="14" y="26" width="6" height="4" rx="1" fill="#FEF08A" />
      {/* Contactless Waves */}
      <Path
        d="M31 26 C33 28 33 30 31 32"
        stroke="#FFFFFF"
        strokeWidth="1.5"
        strokeLinecap="round"
      />
    </Svg>
  );
};

/**
 * 17. FAQ SVG Vector Icon: Bagasi & Kargo
 */
export const FaqLuggageBagIcon: React.FC<IconProps> = ({ size = 36 }) => {
  return (
    <Svg width={size} height={size} viewBox="0 0 48 48" fill="none">
      <Defs>
        <LinearGradient id="faqLuggageGrad" x1="0%" y1="0%" x2="100%" y2="100%">
          <Stop offset="0%" stopColor="#F97316" />
          <Stop offset="100%" stopColor="#EA580C" />
        </LinearGradient>
      </Defs>
      <Rect width="48" height="48" rx="14" fill="#FFF7ED" />
      {/* Handle */}
      <Path d="M20 12 H28 V16 H20 Z" fill="#9A3412" />
      {/* Suitcase Main Body */}
      <Rect
        x="13"
        y="16"
        width="22"
        height="22"
        rx="4"
        fill="url(#faqLuggageGrad)"
      />
      {/* Vertical Ribs */}
      <Path
        d="M19 19 V35"
        stroke="#FFFFFF"
        strokeWidth="1.5"
        opacity="0.6"
        strokeLinecap="round"
      />
      <Path
        d="M24 19 V35"
        stroke="#FFFFFF"
        strokeWidth="1.5"
        opacity="0.6"
        strokeLinecap="round"
      />
      <Path
        d="M29 19 V35"
        stroke="#FFFFFF"
        strokeWidth="1.5"
        opacity="0.6"
        strokeLinecap="round"
      />
      {/* Wheels */}
      <Circle cx="17" cy="39" r="2" fill="#1E293B" />
      <Circle cx="31" cy="39" r="2" fill="#1E293B" />
    </Svg>
  );
};
