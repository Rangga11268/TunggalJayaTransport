import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Dimensions,
  Platform,
  RefreshControl,
  Alert,
  Linking,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { LinearGradient } from "expo-linear-gradient";
import { useNavigation } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import {
  Bell,
  MapPin,
  ChevronRight,
  Sparkles,
  ArrowRight,
  Bus,
  Tag,
  ArrowLeftRight,
  Calendar,
  PhoneCall,
  Copy,
  Check,
  Star,
  ExternalLink,
} from "lucide-react-native";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import { useAuth } from "../context/AuthContext";
import apiClient from "../api/client";
import { SectionHeader } from "../components/SectionHeader";
import {
  AkapBusIcon,
  CharterPariwisataIcon,
  BookingHistoryIcon,
  PromoVoucherIcon,
  AirSuspensionIcon,
  FreeMealBuffetIcon,
  FastChargingIcon,
  FreeCoffeeIcon,
  OfficialWhatsAppIcon,
} from "../components/ServiceIcons";
import { NotificationModal } from "../components/NotificationModal";

const { width } = Dimensions.get("window");
type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function HomeScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { user } = useAuth();

  const [schedules, setSchedules] = useState<any[]>([]);
  const [refreshing, setRefreshing] = useState(false);
  const [couponCopied, setCouponCopied] = useState(false);
  const [isNotifOpen, setIsNotifOpen] = useState(false);
  const [unreadNotifCount, setUnreadNotifCount] = useState(2);

  // Interactive booking search box state
  const [originCity, setOriginCity] = useState("Kuningan");
  const [destinationCity, setDestinationCity] = useState("Jakarta");
  const [selectedDayTab, setSelectedDayTab] = useState<"today" | "tomorrow">(
    "today",
  );

  // Rich Vector SVG Service Icons for Layanan Utama
  const quickLinks = [
    {
      id: "schedules",
      title: "Tiket Bus AKAP",
      subtitle: "Jadwal & Kursi",
      iconComponent: AkapBusIcon,
      action: () => navigation.navigate("Schedules"),
    },
    {
      id: "charter",
      title: "Sewa Pariwisata",
      subtitle: "Carter Rombongan",
      iconComponent: CharterPariwisataIcon,
      action: () => navigation.navigate("Charter"),
    },
    {
      id: "history",
      title: "Riwayat Pesanan",
      subtitle: "Cek E-Tiket",
      iconComponent: BookingHistoryIcon,
      action: () =>
        navigation.navigate("MainTabs", { screen: "BookingHistory" } as any),
    },
    {
      id: "promo",
      title: "Voucher Promo",
      subtitle: "Diskon s.d 50%",
      iconComponent: PromoVoucherIcon,
      action: () => navigation.navigate("Promo"),
    },
  ];

  const fetchHomeData = async () => {
    try {
      const response = await apiClient
        .get("/schedules")
        .catch(() => ({ data: [] }));
      const list = Array.isArray(response.data)
        ? response.data
        : response.data?.data || [];
      setSchedules(list.slice(0, 4));

      // Fetch unread notifications count
      const notifRes = await apiClient
        .get("/notifications/unread-count")
        .catch(() => null);
      if (notifRes?.data?.unread_count !== undefined) {
        setUnreadNotifCount(notifRes.data.unread_count);
      }
    } catch (e) {
      console.log("Error loading home data:", e);
    }
  };

  useEffect(() => {
    fetchHomeData();
  }, []);

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchHomeData();
    setRefreshing(false);
  };

  const handleSwapCities = () => {
    const temp = originCity;
    setOriginCity(destinationCity);
    setDestinationCity(temp);
  };

  const handleCopyCoupon = () => {
    setCouponCopied(true);
    Alert.alert(
      "Kupon Disalin",
      "Kode voucher 'TJBERKAH' berhasil disalin. Gunakan saat checkout untuk mendapatkan potongan harga 10%.",
    );
    setTimeout(() => setCouponCopied(false), 3000);
  };

  return (
    <View style={styles.container}>
      {/* Top Header App Bar (Clean Modern Brand Bar, No Hamburger Clutter) */}
      <SafeAreaView edges={["top"]} style={styles.safeHeader}>
        <View style={styles.headerBar}>
          <View style={styles.headerBrandLeft}>
            <Image
              source={require("../../assets/images/logoNoBg.png")}
              style={styles.headerLogo}
              resizeMode="contain"
            />
          </View>

          <View style={styles.headerRight}>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => setIsNotifOpen(true)}
              style={styles.iconCircle}
            >
              <Bell size={18} color="#111827" />
              {unreadNotifCount > 0 && <View style={styles.badgeDot} />}
            </TouchableOpacity>

            <TouchableOpacity
              activeOpacity={0.8}
              onPress={() =>
                navigation.navigate("MainTabs", { screen: "Profile" } as any)
              }
              style={styles.avatarRing}
            >
              <Image
                source={require("../../assets/images/bentas01.webp")}
                style={styles.avatarImg}
              />
            </TouchableOpacity>
          </View>
        </View>
      </SafeAreaView>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={onRefresh}
            tintColor={COLORS.brandBlue}
          />
        }
      >
        {/* User Greeting & Tagline */}
        <View style={styles.greetingSection}>
          <Text style={styles.greetingTitle}>
            Halo,{" "}
            <Text style={styles.greetingName}>{user?.name || "Sobat TJ"}</Text>
          </Text>
          <Text style={styles.greetingSubtitle}>
            Mau bepergian nyaman kemana hari ini?
          </Text>
        </View>

        {/* 1. INTERACTIVE TRIP SEARCH CARD (Native Travel Booking Box) */}
        <View style={styles.searchCard}>
          <View style={styles.routeSelectorRow}>
            {/* Origin */}
            <TouchableOpacity
              activeOpacity={0.7}
              style={styles.routeCityBox}
              onPress={() =>
                setOriginCity(
                  originCity === "Kuningan" ? "Cirebon" : "Kuningan",
                )
              }
            >
              <Text style={styles.routeBoxLabel}>DARI</Text>
              <Text style={styles.routeBoxCity}>{originCity}</Text>
              <Text style={styles.routeBoxPool}>Pool Cirendang / Terminal</Text>
            </TouchableOpacity>

            {/* Swap Button */}
            <TouchableOpacity
              activeOpacity={0.75}
              onPress={handleSwapCities}
              style={styles.swapCityBtn}
            >
              <ArrowLeftRight size={16} color={COLORS.brandBlue} />
            </TouchableOpacity>

            {/* Destination */}
            <TouchableOpacity
              activeOpacity={0.7}
              style={[styles.routeCityBox, { alignItems: "flex-end" }]}
              onPress={() =>
                setDestinationCity(
                  destinationCity === "Jakarta" ? "Banten (Bitung)" : "Jakarta",
                )
              }
            >
              <Text style={styles.routeBoxLabel}>TUJUAN</Text>
              <Text style={styles.routeBoxCity}>{destinationCity}</Text>
              <Text style={styles.routeBoxPool}>Kalideres / Roxy / Bitung</Text>
            </TouchableOpacity>
          </View>

          {/* Quick Date Selector Strip */}
          <View style={styles.searchDateRow}>
            <TouchableOpacity
              activeOpacity={0.8}
              onPress={() => setSelectedDayTab("today")}
              style={[
                styles.searchDatePill,
                selectedDayTab === "today" && styles.searchDatePillActive,
              ]}
            >
              <Calendar
                size={13}
                color={selectedDayTab === "today" ? "#FFFFFF" : "#6B7280"}
                style={{ marginRight: 5 }}
              />
              <Text
                style={[
                  styles.searchDateText,
                  selectedDayTab === "today" && styles.searchDateTextActive,
                ]}
              >
                Hari Ini
              </Text>
            </TouchableOpacity>

            <TouchableOpacity
              activeOpacity={0.8}
              onPress={() => setSelectedDayTab("tomorrow")}
              style={[
                styles.searchDatePill,
                selectedDayTab === "tomorrow" && styles.searchDatePillActive,
              ]}
            >
              <Calendar
                size={13}
                color={selectedDayTab === "tomorrow" ? "#FFFFFF" : "#6B7280"}
                style={{ marginRight: 5 }}
              />
              <Text
                style={[
                  styles.searchDateText,
                  selectedDayTab === "tomorrow" && styles.searchDateTextActive,
                ]}
              >
                Besok
              </Text>
            </TouchableOpacity>

            <View style={styles.tollBadge}>
              <Text style={styles.tollBadgeText}>Tol Cipali PP</Text>
            </View>
          </View>

          {/* Search CTA Button */}
          <TouchableOpacity
            activeOpacity={0.85}
            onPress={() =>
              navigation.navigate("Schedules", {
                origin: originCity,
                destination: destinationCity,
              })
            }
            style={styles.searchActionBtn}
          >
            <Bus size={17} color="#FFFFFF" style={{ marginRight: 8 }} />
            <Text style={styles.searchActionBtnText}>
              Cari Jadwal Tiket Bus
            </Text>
            <ArrowRight size={16} color="#FFFFFF" style={{ marginLeft: 6 }} />
          </TouchableOpacity>
        </View>

        {/* 2. FLASH PROMO VOUCHER BANNER (Interactive Copy Code) */}
        <View style={styles.flashPromoBanner}>
          <View style={styles.flashPromoLeft}>
            <View style={styles.promoTagCircle}>
              <Tag size={16} color="#2563EB" />
            </View>
            <View style={{ flex: 1, marginLeft: 10 }}>
              <Text style={styles.promoBannerTitle}>
                Diskon 10% Semua Rute AKAP
              </Text>
              <Text style={styles.promoBannerSub}>
                Kode Voucher: <Text style={styles.promoCodeText}>TJBERKAH</Text>
              </Text>
            </View>
          </View>

          <TouchableOpacity
            activeOpacity={0.75}
            onPress={handleCopyCoupon}
            style={[
              styles.copyCouponBtn,
              couponCopied && styles.copyCouponBtnActive,
            ]}
          >
            {couponCopied ? (
              <>
                <Check size={12} color="#FFFFFF" style={{ marginRight: 4 }} />
                <Text style={styles.copyCouponTextActive}>Tersalin</Text>
              </>
            ) : (
              <>
                <Copy size={12} color="#2563EB" style={{ marginRight: 4 }} />
                <Text style={styles.copyCouponText}>Salin</Text>
              </>
            )}
          </TouchableOpacity>
        </View>

        {/* 3. PROMINENT QUICK ACTIONS GRID (Vibrant Multi-layer SVG Icons) */}
        <View style={styles.quickSection}>
          <SectionHeader
            title="Layanan Utama"
            subtitle="Akses Cepat Pemesanan & Informasi"
            style={{ marginBottom: 12 }}
          />

          <View style={styles.quickGrid}>
            {quickLinks.map((item) => {
              const SvgIcon = item.iconComponent;
              return (
                <TouchableOpacity
                  key={item.id}
                  activeOpacity={0.85}
                  onPress={item.action}
                  style={styles.quickCard}
                >
                  <View style={styles.quickIconWrapper}>
                    <SvgIcon size={50} />
                  </View>
                  <Text style={styles.quickTitle} numberOfLines={1}>
                    {item.title}
                  </Text>
                  <Text style={styles.quickSubtitle} numberOfLines={1}>
                    {item.subtitle}
                  </Text>
                </TouchableOpacity>
              );
            })}
          </View>
        </View>

        {/* 4. LUXURY FLEET SHOWCASE BANNER */}
        <TouchableOpacity
          activeOpacity={0.9}
          onPress={() => navigation.navigate("Schedules")}
          style={styles.heroBannerCard}
        >
          <Image
            source={require("../../assets/images/resiBisma.webp")}
            style={styles.heroBannerBg}
            resizeMode="cover"
          />
          <LinearGradient
            colors={["rgba(15, 23, 42, 0.2)", "rgba(15, 23, 42, 0.9)"]}
            style={styles.heroBannerOverlay}
          >
            <View style={styles.heroBadgesRow}>
              <View style={styles.heroGlassBadge}>
                <Sparkles
                  size={11}
                  color="#38BDF8"
                  style={{ marginRight: 4 }}
                />
                <Text style={styles.heroGlassBadgeText}>
                  Adiputro SHD Single Glass
                </Text>
              </View>
              <View style={styles.heroGlassBadge}>
                <Text style={styles.heroGlassBadgeText}>Air Suspension</Text>
              </View>
            </View>

            <View style={styles.heroContentBottom}>
              <View style={{ flex: 1 }}>
                <Text style={styles.heroBannerHeading}>
                  Jetbus 5 Super High Deck
                </Text>
                <Text style={styles.heroBannerSub}>
                  Armada Hino RM 280 • Full AC & Reclining Seat
                </Text>
              </View>

              <View style={styles.heroActionBtn}>
                <Text style={styles.heroActionText}>Pesan</Text>
                <ArrowRight
                  size={14}
                  color="#FFFFFF"
                  style={{ marginLeft: 4 }}
                />
              </View>
            </View>
          </LinearGradient>
        </TouchableOpacity>

        {/* 5. KEUNGGULAN STANDAR PELAYANAN (Elevated Gradient SVG Carousel) */}
        <View style={styles.featuresSection}>
          <SectionHeader
            title="Keunggulan Layanan"
            subtitle="Standar Kenyamanan PO Tunggal Jaya"
            style={{ marginBottom: 12 }}
          />

          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.featuresScroll}
          >
            {/* Card 1: Suspensi Udara */}
            <LinearGradient
              colors={["#F0F7FF", "#FFFFFF"]}
              start={{ x: 0, y: 0 }}
              end={{ x: 1, y: 1 }}
              style={[styles.featureCard, { borderColor: "#BAE6FD" }]}
            >
              <View style={styles.featureTopRow}>
                <View
                  style={[
                    styles.featureSvgWrapper,
                    { backgroundColor: "#E0F2FE" },
                  ]}
                >
                  <AirSuspensionIcon size={38} />
                </View>
                <View
                  style={[styles.microBadge, { backgroundColor: "#E0F2FE" }]}
                >
                  <Text style={[styles.microBadgeText, { color: "#0284C7" }]}>
                    Hino RM 280
                  </Text>
                </View>
              </View>
              <Text style={styles.featureTitle}>Suspensi Udara</Text>
              <Text style={styles.featureDesc}>
                Air suspension empuk & stabil melaju di Tol Cipali.
              </Text>
            </LinearGradient>

            {/* Card 2: Makan Prasmanan */}
            <LinearGradient
              colors={["#F0FDF4", "#FFFFFF"]}
              start={{ x: 0, y: 0 }}
              end={{ x: 1, y: 1 }}
              style={[styles.featureCard, { borderColor: "#BBF7D0" }]}
            >
              <View style={styles.featureTopRow}>
                <View
                  style={[
                    styles.featureSvgWrapper,
                    { backgroundColor: "#DCFCE7" },
                  ]}
                >
                  <FreeMealBuffetIcon size={38} />
                </View>
                <View
                  style={[styles.microBadge, { backgroundColor: "#DCFCE7" }]}
                >
                  <Text style={[styles.microBadgeText, { color: "#16A34A" }]}>
                    KM 166
                  </Text>
                </View>
              </View>
              <Text style={styles.featureTitle}>Makan Prasmanan</Text>
              <Text style={styles.featureDesc}>
                Gratis servis makan prasmanan di Rest Area KM 166.
              </Text>
            </LinearGradient>

            {/* Card 3: USB Fast Charging */}
            <LinearGradient
              colors={["#FFFBEB", "#FFFFFF"]}
              start={{ x: 0, y: 0 }}
              end={{ x: 1, y: 1 }}
              style={[styles.featureCard, { borderColor: "#FDE68A" }]}
            >
              <View style={styles.featureTopRow}>
                <View
                  style={[
                    styles.featureSvgWrapper,
                    { backgroundColor: "#FEF3C7" },
                  ]}
                >
                  <FastChargingIcon size={38} />
                </View>
                <View
                  style={[styles.microBadge, { backgroundColor: "#FEF3C7" }]}
                >
                  <Text style={[styles.microBadgeText, { color: "#D97706" }]}>
                    Fast 18W
                  </Text>
                </View>
              </View>
              <Text style={styles.featureTitle}>USB Fast Charging</Text>
              <Text style={styles.featureDesc}>
                Port charger HP di setiap bangku selama perjalanan.
              </Text>
            </LinearGradient>

            {/* Card 4: Kopi & Air Mineral */}
            <LinearGradient
              colors={["#FAF5FF", "#FFFFFF"]}
              start={{ x: 0, y: 0 }}
              end={{ x: 1, y: 1 }}
              style={[styles.featureCard, { borderColor: "#DDD6FE" }]}
            >
              <View style={styles.featureTopRow}>
                <View
                  style={[
                    styles.featureSvgWrapper,
                    { backgroundColor: "#F3E8FF" },
                  ]}
                >
                  <FreeCoffeeIcon size={38} />
                </View>
                <View
                  style={[styles.microBadge, { backgroundColor: "#F3E8FF" }]}
                >
                  <Text style={[styles.microBadgeText, { color: "#9333EA" }]}>
                    Gratis
                  </Text>
                </View>
              </View>
              <Text style={styles.featureTitle}>Kopi & Air Mineral</Text>
              <Text style={styles.featureDesc}>
                Fasilitas kopi & air mineral gratis di perjalanan.
              </Text>
            </LinearGradient>
          </ScrollView>
        </View>

        {/* 6. WHAT'S NEW STORIES SECTION */}
        <SectionHeader
          title="Kabar & Cerita"
          actionLabel="Lihat Semua >"
          onAction={() => navigation.navigate("Promo")}
        />

        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.whatsNewScroll}
        >
          <TouchableOpacity
            activeOpacity={0.85}
            onPress={() => navigation.navigate("Schedules")}
            style={styles.storyCard}
          >
            <Image
              source={require("../../assets/images/bentas01.webp")}
              style={styles.storyImage}
              resizeMode="cover"
            />
            <LinearGradient
              colors={["transparent", "rgba(17, 24, 39, 0.88)"]}
              style={styles.storyGradient}
            >
              <Text style={styles.storyTitle}>Bentas-01 Kuningan - Jkt</Text>
              <Text style={styles.storySubtitle}>
                Jadwal harian rute favorit via Tol Cipali
              </Text>
              <View style={styles.storyPill}>
                <Text style={styles.storyPillText}>Cek Jadwal &gt;</Text>
              </View>
            </LinearGradient>
          </TouchableOpacity>

          <TouchableOpacity
            activeOpacity={0.85}
            onPress={() => navigation.navigate("Charter")}
            style={styles.storyCard}
          >
            <Image
              source={require("../../assets/images/kylorenParwis.webp")}
              style={styles.storyImage}
              resizeMode="cover"
            />
            <LinearGradient
              colors={["transparent", "rgba(17, 24, 39, 0.88)"]}
              style={styles.storyGradient}
            >
              <Text style={styles.storyTitle}>Kylo Ren Jetbus 5 SHD</Text>
              <Text style={styles.storySubtitle}>
                Armada pariwisata Hino RM 280
              </Text>
              <View style={styles.storyPill}>
                <Text style={styles.storyPillText}>Sewa Unit &gt;</Text>
              </View>
            </LinearGradient>
          </TouchableOpacity>
        </ScrollView>

        {/* 7. POPULAR ROUTES LIST */}
        <SectionHeader
          title="Rute Populer AKAP"
          actionLabel="Lihat Semua >"
          onAction={() => navigation.navigate("Schedules")}
        />

        <View style={styles.scheduleList}>
          {schedules.map((item, idx) => {
            const busName =
              item.bus?.name || (idx % 2 === 0 ? "Resi Bisma" : "Primadona");
            const origin = item.route?.origin || "Kuningan";
            const destination =
              item.route?.destination ||
              (idx % 2 === 0 ? "Jakarta (Kalideres)" : "Jakarta (Roxy)");
            const price = Number(item.price || 140000).toLocaleString("id-ID");
            const depTime = item.departure_time
              ? item.departure_time.substring(11, 16) ||
                item.departure_time.substring(0, 5)
              : "07:45";

            return (
              <TouchableOpacity
                key={item.id || idx}
                activeOpacity={0.85}
                onPress={() =>
                  navigation.navigate("ScheduleDetail", {
                    scheduleId: item.id || 1,
                  })
                }
                style={styles.scheduleCard}
              >
                <Image
                  source={
                    idx % 2 === 0
                      ? require("../../assets/images/resiBisma.webp")
                      : require("../../assets/images/primadona.webp")
                  }
                  style={styles.scheduleBusThumb}
                />
                <View style={styles.scheduleMiddle}>
                  <Text style={styles.scheduleRoute}>
                    {origin} → {destination}
                  </Text>
                  <Text style={styles.scheduleBusName}>
                    {busName} • {depTime} WIB
                  </Text>
                  <Text style={styles.schedulePrice}>
                    Rp {price}{" "}
                    <Text style={styles.schedulePerPerson}>/ org</Text>
                  </Text>
                </View>
                <View style={styles.scheduleAction}>
                  <View style={styles.ratingBadge}>
                    <Star
                      size={11}
                      color="#D97706"
                      fill="#D97706"
                      style={{ marginRight: 3 }}
                    />
                    <Text style={styles.ratingText}>4.9</Text>
                  </View>
                  <View style={styles.chevronCircle}>
                    <ChevronRight size={16} color="#FFFFFF" />
                  </View>
                </View>
              </TouchableOpacity>
            );
          })}
        </View>

        {/* 8. LOKASI GARASI & KONTAK RESMI (Authentic PO Tunggal Jaya Data & Real SVG Brand Icons) */}
        <View style={styles.garageSectionContainer}>
          <SectionHeader
            title="Garasi & Kontak Resmi"
            subtitle="Lokasi Operasional & Layanan Bantuan 24 Jam"
            style={{ marginBottom: 12 }}
          />

          <View style={styles.garageCardBox}>
            {/* Garasi 1 */}
            <View style={styles.garageItem}>
              <View style={styles.garageBadgeRow}>
                <View style={styles.garageBadgePill}>
                  <Text style={styles.garageBadgePillText}>
                    GARASI 1 (PUSAT & PARIWISATA)
                  </Text>
                </View>
              </View>
              <Text style={styles.garageItemTitle}>
                Garasi Pusat Cilimus, Kuningan
              </Text>
              <Text style={styles.garageItemAddress}>
                Jl. Raya Linggajati, Bojong, Kec. Cilimus, Kabupaten Kuningan,
                Jawa Barat 45556
              </Text>
            </View>

            <View style={styles.garageItemDivider} />

            {/* Garasi 2 */}
            <View style={styles.garageItem}>
              <View style={styles.garageBadgeRow}>
                <View
                  style={[
                    styles.garageBadgePill,
                    { backgroundColor: "#F1F5F9" },
                  ]}
                >
                  <Text
                    style={[styles.garageBadgePillText, { color: "#475569" }]}
                  >
                    GARASI 2 (KHUSUS BUS AKAP)
                  </Text>
                </View>
              </View>
              <Text style={styles.garageItemTitle}>
                Garasi Cidahu, Kuningan
              </Text>
              <Text style={styles.garageItemAddress}>
                Cihideunggirang, Kec. Cidahu, Kabupaten Kuningan, Jawa Barat
                45595 • Pool AKAP & Bengkel Terpadu
              </Text>
            </View>

            {/* Dedicated Action Buttons (Full-width clean row, no text clipping) */}
            <View style={styles.garageActionsWrapper}>
              <TouchableOpacity
                activeOpacity={0.85}
                onPress={() =>
                  Linking.openURL(
                    "https://wa.me/6281122222353?text=Halo%20CS%20PO%20Tunggal%20Jaya,%20saya%20ingin%20informasi%20jadwal%20dan%20sewa%20bus.",
                  )
                }
                style={styles.officialWaButton}
              >
                <OfficialWhatsAppIcon size={18} color="#FFFFFF" />
                <Text style={styles.officialWaButtonText}>
                  Chat WhatsApp CS 24 Jam
                </Text>
                <ExternalLink
                  size={13}
                  color="#FFFFFF"
                  style={{ marginLeft: 4 }}
                />
              </TouchableOpacity>

              <View style={styles.garageSecondaryButtonsRow}>
                <TouchableOpacity
                  activeOpacity={0.8}
                  onPress={() => Linking.openURL("tel:0232613399")}
                  style={styles.officialCallButton}
                >
                  <PhoneCall
                    size={14}
                    color="#1E293B"
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.officialCallButtonText}>
                    Telepon (0232) 613399
                  </Text>
                </TouchableOpacity>

                <TouchableOpacity
                  activeOpacity={0.8}
                  onPress={() =>
                    Linking.openURL(
                      "https://maps.google.com/?q=-6.881759,108.491583",
                    )
                  }
                  style={styles.officialMapsButton}
                >
                  <MapPin
                    size={14}
                    color={COLORS.brandBlue}
                    style={{ marginRight: 5 }}
                  />
                  <Text style={styles.officialMapsButtonText}>Rute Maps</Text>
                </TouchableOpacity>
              </View>
            </View>
          </View>
        </View>

        <View style={{ height: 110 }} />
      </ScrollView>

      {/* Realtime Notification Modal */}
      <NotificationModal
        visible={isNotifOpen}
        onClose={() => setIsNotifOpen(false)}
        onNavigateToBooking={() =>
          navigation.navigate("MainTabs", { screen: "BookingHistory" } as any)
        }
        onNavigateToPromo={() => navigation.navigate("Promo")}
        onUpdateUnreadCount={setUnreadNotifCount}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  safeHeader: {
    backgroundColor: "#FFFFFF",
    zIndex: 50,
  },
  headerBar: {
    height: 58,
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 16,
    borderBottomWidth: 1,
    borderBottomColor: "#E2E8F0",
  },
  headerBrandLeft: {
    flex: 1,
    justifyContent: "center",
  },
  headerLogo: {
    width: 130,
    height: 30,
  },
  headerRight: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
  },
  iconCircle: {
    width: 38,
    height: 38,
    borderRadius: 19,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
    position: "relative",
  },
  badgeDot: {
    width: 8,
    height: 8,
    borderRadius: 4,
    backgroundColor: "#DC2626",
    position: "absolute",
    top: 6,
    right: 7,
  },
  avatarRing: {
    width: 38,
    height: 38,
    borderRadius: 19,
    borderWidth: 1.5,
    borderColor: COLORS.brandBlue,
    overflow: "hidden",
  },
  avatarImg: {
    width: "100%",
    height: "100%",
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingTop: 16,
  },
  greetingSection: {
    marginBottom: 14,
  },
  greetingTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 20,
    color: "#111827",
    letterSpacing: -0.5,
  },
  greetingName: {
    color: COLORS.brandBlue,
  },
  greetingSubtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13,
    color: "#6B7280",
    marginTop: 2,
  },
  searchCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 14,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.06,
        shadowRadius: 10,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  routeSelectorRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#F8FAFC",
    borderRadius: 14,
    padding: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 12,
  },
  routeCityBox: {
    flex: 1,
  },
  routeBoxLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#9CA3AF",
    letterSpacing: 0.5,
  },
  routeBoxCity: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 15,
    color: "#111827",
    marginTop: 1,
  },
  routeBoxPool: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#6B7280",
    marginTop: 2,
  },
  swapCityBtn: {
    width: 34,
    height: 34,
    borderRadius: 17,
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
    marginHorizontal: 8,
  },
  searchDateRow: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 14,
    gap: 8,
  },
  searchDatePill: {
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 12,
    paddingVertical: 7,
    borderRadius: 10,
    backgroundColor: "#F1F5F9",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  searchDatePillActive: {
    backgroundColor: COLORS.brandBlue,
    borderColor: COLORS.brandBlue,
  },
  searchDateText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11.5,
    color: "#4B5563",
  },
  searchDateTextActive: {
    color: "#FFFFFF",
    fontFamily: "PlusJakartaSans_700Bold",
  },
  tollBadge: {
    marginLeft: "auto",
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 6,
  },
  tollBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10.5,
    color: COLORS.brandBlue,
  },
  searchActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: COLORS.brandBlue,
    paddingVertical: 12,
    borderRadius: 14,
  },
  searchActionBtnText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: "#FFFFFF",
  },
  flashPromoBanner: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#EFF6FF",
    borderRadius: 14,
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderWidth: 1,
    borderColor: "#BFDBFE",
    marginBottom: 18,
  },
  flashPromoLeft: {
    flex: 1,
    flexDirection: "row",
    alignItems: "center",
  },
  promoTagCircle: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: "#FFFFFF",
    justifyContent: "center",
    alignItems: "center",
  },
  promoBannerTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#1E3A8A",
  },
  promoBannerSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#3B82F6",
  },
  promoCodeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    color: "#1D4ED8",
  },
  copyCouponBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 8,
    borderWidth: 1,
    borderColor: "#BFDBFE",
  },
  copyCouponBtnActive: {
    backgroundColor: "#059669",
    borderColor: "#059669",
  },
  copyCouponText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#2563EB",
  },
  copyCouponTextActive: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#FFFFFF",
  },
  quickSection: {
    marginBottom: 20,
  },
  quickGrid: {
    flexDirection: "row",
    justifyContent: "space-between",
    gap: 8,
  },
  quickCard: {
    flex: 1,
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    borderRadius: 16,
    paddingVertical: 12,
    paddingHorizontal: 4,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.04,
        shadowRadius: 6,
      },
      android: {
        elevation: 1,
      },
    }),
  },
  quickIconWrapper: {
    width: 52,
    height: 52,
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 6,
  },
  quickTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#111827",
    textAlign: "center",
  },
  quickSubtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 9.5,
    color: "#6B7280",
    textAlign: "center",
    marginTop: 2,
  },
  heroBannerCard: {
    height: 160,
    borderRadius: 18,
    overflow: "hidden",
    marginBottom: 22,
    position: "relative",
  },
  heroBannerBg: {
    width: "100%",
    height: "100%",
  },
  heroBannerOverlay: {
    ...StyleSheet.absoluteFillObject,
    padding: 16,
    justifyContent: "space-between",
  },
  heroBadgesRow: {
    flexDirection: "row",
    gap: 8,
  },
  heroGlassBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(15, 23, 42, 0.65)",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
    borderWidth: 1,
    borderColor: "rgba(255, 255, 255, 0.2)",
  },
  heroGlassBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#FFFFFF",
  },
  heroContentBottom: {
    flexDirection: "row",
    alignItems: "flex-end",
    justifyContent: "space-between",
  },
  heroBannerHeading: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#FFFFFF",
  },
  heroBannerSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "rgba(255, 255, 255, 0.85)",
    marginTop: 2,
  },
  heroActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 10,
  },
  heroActionText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11.5,
    color: "#FFFFFF",
  },
  featuresSection: {
    marginBottom: 22,
  },
  featuresScroll: {
    flexDirection: "row",
    gap: 12,
    paddingRight: 16,
  },
  featureCard: {
    width: 185,
    borderRadius: 20,
    padding: 15,
    borderWidth: 1.5,
    marginRight: 2,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.05,
        shadowRadius: 10,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  featureTopRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    marginBottom: 12,
  },
  featureSvgWrapper: {
    width: 44,
    height: 44,
    borderRadius: 14,
    justifyContent: "center",
    alignItems: "center",
  },
  microBadge: {
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 8,
  },
  microBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    letterSpacing: 0.2,
  },
  featureTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: "#0F172A",
    marginBottom: 4,
  },
  featureDesc: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#475569",
    lineHeight: 16,
  },
  whatsNewScroll: {
    flexDirection: "row",
    gap: 12,
    paddingBottom: 22,
  },
  storyCard: {
    width: 220,
    height: 120,
    borderRadius: 16,
    overflow: "hidden",
    position: "relative",
  },
  storyImage: {
    width: "100%",
    height: "100%",
  },
  storyGradient: {
    ...StyleSheet.absoluteFillObject,
    padding: 12,
    justifyContent: "flex-end",
  },
  storyTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#FFFFFF",
  },
  storySubtitle: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 10,
    color: "rgba(255, 255, 255, 0.8)",
    marginTop: 1,
  },
  storyPill: {
    alignSelf: "flex-start",
    backgroundColor: "rgba(255, 255, 255, 0.25)",
    paddingHorizontal: 6,
    paddingVertical: 2,
    borderRadius: 4,
    marginTop: 4,
  },
  storyPillText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9,
    color: "#FFFFFF",
  },
  scheduleList: {
    gap: 10,
    marginBottom: 20,
  },
  scheduleCard: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    borderRadius: 16,
    padding: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  scheduleBusThumb: {
    width: 58,
    height: 58,
    borderRadius: 12,
  },
  scheduleMiddle: {
    flex: 1,
    marginLeft: 12,
  },
  scheduleRoute: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#111827",
  },
  scheduleBusName: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
    marginTop: 2,
  },
  schedulePrice: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: COLORS.brandBlue,
    marginTop: 3,
  },
  schedulePerPerson: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 10,
    color: "#6B7280",
  },
  scheduleAction: {
    alignItems: "flex-end",
    gap: 6,
  },
  ratingBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(217, 119, 6, 0.1)",
    paddingHorizontal: 6,
    paddingVertical: 2,
    borderRadius: 6,
  },
  ratingText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#D97706",
  },
  chevronCircle: {
    width: 28,
    height: 28,
    borderRadius: 14,
    backgroundColor: COLORS.brandBlue,
    justifyContent: "center",
    alignItems: "center",
  },
  garageSectionContainer: {
    marginBottom: 20,
  },
  garageCardBox: {
    backgroundColor: "#FFFFFF",
    borderRadius: 22,
    padding: 18,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.05,
        shadowRadius: 10,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  garageItem: {
    marginBottom: 6,
  },
  garageBadgeRow: {
    marginBottom: 6,
  },
  garageBadgePill: {
    alignSelf: "flex-start",
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
  },
  garageBadgePillText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#2563EB",
    letterSpacing: 0.3,
  },
  garageItemTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#111827",
  },
  garageItemAddress: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#6B7280",
    marginTop: 3,
    lineHeight: 17,
  },
  garageItemDivider: {
    height: 1,
    backgroundColor: "#F1F5F9",
    marginVertical: 12,
  },
  garageActionsWrapper: {
    marginTop: 14,
    gap: 8,
  },
  officialWaButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#16A34A",
    paddingVertical: 12,
    borderRadius: 14,
    gap: 8,
  },
  officialWaButtonText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  garageSecondaryButtonsRow: {
    flexDirection: "row",
    gap: 8,
  },
  officialCallButton: {
    flex: 1,
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#F8FAFC",
    paddingVertical: 10,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  officialCallButtonText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11.5,
    color: "#1E293B",
  },
  officialMapsButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#BFDBFE",
  },
  officialMapsButtonText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11.5,
    color: COLORS.brandBlue,
  },
});
