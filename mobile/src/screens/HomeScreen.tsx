import React, { useState, useEffect } from "react";
import { View, StyleSheet, ScrollView, RefreshControl } from "react-native";
import { useNavigation } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import { useAuth } from "../context/AuthContext";
import { useRewards } from "../context/RewardContext";
import { useCustomAlert } from "../context/AlertContext";
import apiClient from "../api/client";
import { NotificationModal } from "../components/NotificationModal";

// Modular Home Components
import { HomeTopBar } from "../components/home/HomeTopBar";
import { HomeBookingCard } from "../components/home/HomeBookingCard";
import { HomePromoBanner } from "../components/home/HomePromoBanner";
import { HomeServicesGrid } from "../components/home/HomeServicesGrid";
import { HomeFleetShowcase } from "../components/home/HomeFleetShowcase";
import { HomeFacilities } from "../components/home/HomeFacilities";
import { HomeStoriesSection } from "../components/home/HomeStoriesSection";
import { HomePopularRoutes } from "../components/home/HomePopularRoutes";
import { HomeGaragesSection } from "../components/home/HomeGaragesSection";

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function HomeScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { user } = useAuth();
  const { points } = useRewards();
  const { showSuccess } = useCustomAlert();

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

  const getGreeting = () => {
    const hour = new Date().getHours();
    if (hour < 11) return "Selamat Pagi";
    if (hour < 15) return "Selamat Siang";
    if (hour < 18) return "Selamat Sore";
    return "Selamat Malam";
  };

  const handleCopyCoupon = () => {
    setCouponCopied(true);
    showSuccess(
      "Kupon Berhasil Disalin",
      "Gunakan kode kupon promo ini saat checkout pemesanan tiket.\n\nKode Voucher: TJBERKAH\n\nDapatkan diskon potongan 10% langsung pada transaksi Anda.",
    );
    setTimeout(() => setCouponCopied(false), 3000);
  };

  return (
    <View style={styles.container}>
      {/* Top Header App Bar */}
      <HomeTopBar
        user={user}
        points={points}
        unreadNotifCount={unreadNotifCount}
        onOpenNotif={() => setIsNotifOpen(true)}
        onOpenProfile={() =>
          navigation.navigate("MainTabs", { screen: "Profile" } as any)
        }
        onOpenRewards={() => navigation.navigate("Rewards")}
        onLogin={() => navigation.navigate("Login")}
      />

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
        {/* 1. Trip Booking Search Card */}
        <HomeBookingCard
          user={user}
          originCity={originCity}
          destinationCity={destinationCity}
          selectedDayTab={selectedDayTab}
          getGreeting={getGreeting}
          onOpenRewards={() => navigation.navigate("Rewards")}
          onSetOriginCity={setOriginCity}
          onSetDestinationCity={setDestinationCity}
          onSwapCities={handleSwapCities}
          onSelectDayTab={setSelectedDayTab}
          onSearchSchedules={() =>
            navigation.navigate("Schedules", {
              origin: originCity,
              destination: destinationCity,
            })
          }
        />

        {/* 2. Flash Promo Voucher Banner */}
        <HomePromoBanner
          couponCopied={couponCopied}
          onCopyCoupon={handleCopyCoupon}
        />

        {/* 3. Prominent Quick Actions Grid */}
        <HomeServicesGrid
          onNavigateSchedules={() => navigation.navigate("Schedules")}
          onNavigateCharter={() => navigation.navigate("Charter")}
          onNavigateHistory={() =>
            navigation.navigate("MainTabs", { screen: "BookingHistory" } as any)
          }
          onNavigatePromo={() => navigation.navigate("Promo")}
        />

        {/* 4. Luxury Fleet Showcase Banner */}
        <HomeFleetShowcase onPress={() => navigation.navigate("Schedules")} />

        {/* 5. Luxury Facilities Standard */}
        <HomeFacilities />

        {/* 6. What's New Stories Section */}
        <HomeStoriesSection
          onNavigatePromo={() => navigation.navigate("Promo")}
          onNavigateSchedules={() => navigation.navigate("Schedules")}
          onNavigateCharter={() => navigation.navigate("Charter")}
        />

        {/* 7. Popular Routes List */}
        <HomePopularRoutes
          schedules={schedules}
          onNavigateSchedules={() => navigation.navigate("Schedules")}
          onSelectSchedule={(scheduleId: number) =>
            navigation.navigate("ScheduleDetail", {
              scheduleId,
            })
          }
        />

        {/* 8. Official Garages & 24/7 Contacts */}
        <HomeGaragesSection />
      </ScrollView>

      {/* Modern Slide-Over Notification Modal */}
      <NotificationModal
        visible={isNotifOpen}
        onClose={() => setIsNotifOpen(false)}
        onUpdateUnreadCount={setUnreadNotifCount}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#F8FAFC",
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingTop: 16,
    paddingBottom: 110,
    width: "100%",
    maxWidth: 680,
    alignSelf: "center",
  },
});
