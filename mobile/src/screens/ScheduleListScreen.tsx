import React, { useState, useEffect, useMemo } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  TextInput,
  Image,
  Dimensions,
  Platform,
  ActivityIndicator,
  RefreshControl,
  Alert,
} from "react-native";
import { useNavigation, useRoute } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import apiClient from "../api/client";
import { ScreenHeader } from "../components/ScreenHeader";
import { EmptyState } from "../components/EmptyState";
import {
  ScheduleFilterModal,
  FilterOptions,
} from "../components/ScheduleFilterModal";
import {
  Search,
  X,
  SlidersHorizontal,
  ArrowUpDown,
  Clock,
  MapPin,
  Sparkles,
  ChevronRight,
  Bus,
  Calendar,
  Navigation,
  ArrowRight,
  ArrowLeftRight,
  CheckCircle2,
  ShieldCheck,
  Flame,
  Star,
} from "lucide-react-native";

const { width } = Dimensions.get("window");
type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

// Official route transit stops for Tunggal Jaya routes
const ROUTE_STOPS_MAP: Record<string, string[]> = {
  kalideres: [
    "Luragung",
    "Oleced",
    "Cirendang",
    "Tol Cipali",
    "Pesing",
    "Kalideres",
  ],
  roxy: [
    "Ciawi",
    "Oleced",
    "Cirendang",
    "Tol Cipali",
    "Roxy",
    "Jembatan 5",
    "Season City",
  ],
  banten: [
    "Luragung",
    "Cirendang",
    "Cikande",
    "Balaraja",
    "Bitung",
    "Rangkasbitung",
  ],
  cirebon: [
    "Ciledug",
    "Pabuaran",
    "Karang Sembung",
    "Tol Cipali",
    "Jatibening",
    "Pangkalan Asem",
  ],
  pulogebang: [
    "Cirendang",
    "Cilimus",
    "Garasi Tunggal Jaya",
    "Tol Cipali",
    "Pulogebang",
  ],
};

// Generate calendar dates for the next 7 days
const getAvailableDates = () => {
  const dates = [];
  const now = new Date();
  const dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
  const monthNames = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "Mei",
    "Jun",
    "Jul",
    "Agu",
    "Sep",
    "Okt",
    "Nov",
    "Des",
  ];

  for (let i = 0; i < 7; i++) {
    const d = new Date(now);
    d.setDate(now.getDate() + i);
    const dateStr = d.toISOString().split("T")[0];

    dates.push({
      dateStr,
      isToday: i === 0,
      isTomorrow: i === 1,
      dayNum: d.getDate(),
      monthName: monthNames[d.getMonth()],
      dayName: i === 0 ? "Hari Ini" : i === 1 ? "Besok" : dayNames[d.getDay()],
    });
  }
  return dates;
};

export default function ScheduleListScreen() {
  const navigation = useNavigation<NavigationProp>();
  const route = useRoute();
  const { origin: paramOrigin, destination: paramDest } = (route.params ||
    {}) as any;

  // Selected route cities
  const [originCity, setOriginCity] = useState<string>(
    paramOrigin || "Kuningan",
  );
  const [destCity, setDestCity] = useState<string>(paramDest || "Jakarta");

  // Dates state
  const dateOptions = useMemo(() => getAvailableDates(), []);
  const [selectedDate, setSelectedDate] = useState<string>(
    dateOptions[0].dateStr,
  );

  // Schedules state
  const [schedules, setSchedules] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  // Search & Filter State
  const [searchQuery, setSearchQuery] = useState("");
  const [filterModalVisible, setFilterModalVisible] = useState(false);
  const [filters, setFilters] = useState<FilterOptions>({
    sortBy: "earliest",
    timeSlot: "all",
    availableOnly: false,
    destinationArea: "all",
  });

  useEffect(() => {
    fetchSchedules();
  }, [selectedDate]);

  const fetchSchedules = async () => {
    try {
      setLoading(true);
      const res = await apiClient.get("/schedules", {
        params: { date: selectedDate },
      });
      const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
      setSchedules(list);
    } catch (e) {
      console.log("Error fetching schedules:", e);
    } finally {
      setLoading(false);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchSchedules();
    setRefreshing(false);
  };

  // Swap origin and destination
  const handleSwapRoute = () => {
    const temp = originCity;
    setOriginCity(destCity);
    setDestCity(temp);
  };

  // Helper to get stops preview based on destination/origin
  const getStopsPreview = (origin: string, dest: string) => {
    const combined = `${origin} ${dest}`.toLowerCase();
    if (combined.includes("roxy") || combined.includes("jembatan"))
      return ROUTE_STOPS_MAP.roxy;
    if (
      combined.includes("banten") ||
      combined.includes("bitung") ||
      combined.includes("rangkas")
    )
      return ROUTE_STOPS_MAP.banten;
    if (combined.includes("cirebon") || combined.includes("ciledug"))
      return ROUTE_STOPS_MAP.cirebon;
    if (combined.includes("pulogebang")) return ROUTE_STOPS_MAP.pulogebang;
    return ROUTE_STOPS_MAP.kalideres;
  };

  // Check if a specific schedule is departed
  const isScheduleDeparted = (item: any) => {
    if (item.is_departed || item.has_departed) return true;
    if (item.status === "departed" || item.status === "cancelled") return true;

    const now = new Date();
    const todayStr = now.toISOString().split("T")[0];

    if (selectedDate < todayStr) return true;

    if (selectedDate === todayStr) {
      const depTimeStr = item.departure_time
        ? item.departure_time.substring(11, 16) ||
          item.departure_time.substring(0, 5)
        : "07:00";
      const [depH, depM] = depTimeStr.split(":").map(Number);

      const currentWibHours = (now.getUTCHours() + 7) % 24;
      const currentWibMinutes = now.getUTCMinutes();

      if (
        currentWibHours > depH ||
        (currentWibHours === depH && currentWibMinutes >= depM)
      ) {
        return true;
      }
    }

    return false;
  };

  // Filtered & Sorted schedules
  const processedSchedules = useMemo(() => {
    let result = schedules.filter((item) => {
      const busName = (item.bus?.name || "").toLowerCase();
      const origin = (item.route?.origin || "").toLowerCase();
      const dest = (item.route?.destination || "").toLowerCase();
      const isDeparted = isScheduleDeparted(item);

      // Search match
      const q = searchQuery.toLowerCase().trim();
      const matchesSearch =
        !q ||
        busName.includes(q) ||
        origin.includes(q) ||
        dest.includes(q) ||
        getStopsPreview(origin, dest).some((s) => s.toLowerCase().includes(q));

      if (!matchesSearch) return false;

      // Available only filter
      if (filters.availableOnly && isDeparted) return false;

      // Time Slot filter
      if (filters.timeSlot !== "all") {
        const depTimeStr = item.departure_time
          ? item.departure_time.substring(11, 16) ||
            item.departure_time.substring(0, 5)
          : "07:00";
        const hour = parseInt(depTimeStr.split(":")[0], 10);

        if (filters.timeSlot === "morning" && (hour < 6 || hour >= 12))
          return false;
        if (filters.timeSlot === "afternoon" && (hour < 12 || hour >= 18))
          return false;
        if (filters.timeSlot === "evening" && (hour < 18 || hour >= 24))
          return false;
      }

      // Destination Area filter
      if (filters.destinationArea === "jakarta") {
        if (
          !dest.includes("jakarta") &&
          !dest.includes("kalideres") &&
          !dest.includes("roxy") &&
          !dest.includes("pulogebang")
        )
          return false;
      } else if (filters.destinationArea === "banten") {
        if (
          !dest.includes("banten") &&
          !dest.includes("bitung") &&
          !dest.includes("rangkasbitung")
        )
          return false;
      } else if (filters.destinationArea === "cirebon") {
        if (!origin.includes("cirebon") && !origin.includes("ciledug"))
          return false;
      }

      return true;
    });

    // Sorting
    result.sort((a, b) => {
      const timeA = a.departure_time || "07:00";
      const timeB = b.departure_time || "07:00";
      const priceA = Number(a.price || 140000);
      const priceB = Number(b.price || 140000);

      if (filters.sortBy === "earliest") {
        return timeA.localeCompare(timeB);
      } else if (filters.sortBy === "latest") {
        return timeB.localeCompare(timeA);
      } else if (filters.sortBy === "cheapest") {
        return priceA - priceB;
      }
      return 0;
    });

    return result;
  }, [schedules, searchQuery, filters, selectedDate]);

  const activeFilterCount = useMemo(() => {
    let count = 0;
    if (filters.sortBy !== "earliest") count++;
    if (filters.timeSlot !== "all") count++;
    if (filters.availableOnly) count++;
    if (filters.destinationArea !== "all") count++;
    return count;
  }, [filters]);

  const tomorrowOption =
    dateOptions.find((d) => d.isTomorrow) || dateOptions[1];

  return (
    <View style={styles.container}>
      {/* Standard Screen Header */}
      <ScreenHeader
        title="Jadwal Keberangkatan"
        subtitle="PO Tunggal Jaya Transport"
        showBack={navigation.canGoBack()}
      />

      {/* 4. MAIN SCHEDULES & FILTERS SCROLL CONTAINER */}
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
        {/* 1. NATIVE TRAVEL APP TRIP SUMMARY CARD */}
        <View style={styles.tripSummaryHeader}>
          <View style={styles.routeSwapRow}>
            <View style={styles.routeCityBox}>
              <Text style={styles.routeCityLabel}>DARI</Text>
              <Text style={styles.routeCityName}>{originCity}</Text>
            </View>

            <TouchableOpacity
              activeOpacity={0.7}
              onPress={handleSwapRoute}
              style={styles.swapBtn}
            >
              <ArrowLeftRight size={16} color={COLORS.brandBlue} />
            </TouchableOpacity>

            <View style={[styles.routeCityBox, { alignItems: "flex-end" }]}>
              <Text style={styles.routeCityLabel}>TUJUAN</Text>
              <Text style={styles.routeCityName}>{destCity}</Text>
            </View>
          </View>

          {/* 2. CALENDAR DATE RIBBON CAROUSEL */}
          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.dateRibbonScroll}
          >
            {dateOptions.map((item) => {
              const isSelected = selectedDate === item.dateStr;
              return (
                <TouchableOpacity
                  key={item.dateStr}
                  activeOpacity={0.8}
                  onPress={() => setSelectedDate(item.dateStr)}
                  style={[styles.dateCard, isSelected && styles.dateCardActive]}
                >
                  <Text
                    style={[
                      styles.dateDayText,
                      isSelected && styles.dateDayTextActive,
                    ]}
                  >
                    {item.dayName}
                  </Text>
                  <Text
                    style={[
                      styles.dateNumText,
                      isSelected && styles.dateNumTextActive,
                    ]}
                  >
                    {item.dayNum}
                  </Text>
                  <Text
                    style={[
                      styles.dateMonthText,
                      isSelected && styles.dateMonthTextActive,
                    ]}
                  >
                    {item.monthName}
                  </Text>
                  <View
                    style={[
                      styles.dateFareDot,
                      isSelected && styles.dateFareDotActive,
                    ]}
                  >
                    <Text
                      style={[
                        styles.dateFareText,
                        isSelected && styles.dateFareTextActive,
                      ]}
                    >
                      140rb
                    </Text>
                  </View>
                </TouchableOpacity>
              );
            })}
          </ScrollView>
        </View>

        {/* 3. FILTER & SEARCH TOOLBAR */}
        <View style={styles.toolbarSection}>
          <View style={styles.searchBar}>
            <Search size={16} color="#6B7280" style={{ marginRight: 6 }} />
            <TextInput
              style={styles.searchInput}
              placeholder="Cari armada / terminal..."
              placeholderTextColor="#9CA3AF"
              value={searchQuery}
              onChangeText={setSearchQuery}
            />
            {searchQuery ? (
              <TouchableOpacity onPress={() => setSearchQuery("")}>
                <X size={14} color="#6B7280" />
              </TouchableOpacity>
            ) : null}
          </View>

          <TouchableOpacity
            activeOpacity={0.8}
            onPress={() => setFilterModalVisible(true)}
            style={[
              styles.filterTriggerBtn,
              activeFilterCount > 0 && styles.filterTriggerBtnActive,
            ]}
          >
            <SlidersHorizontal
              size={14}
              color={activeFilterCount > 0 ? "#FFFFFF" : "#374151"}
              style={{ marginRight: 5 }}
            />
            <Text
              style={[
                styles.filterTriggerText,
                activeFilterCount > 0 && styles.filterTriggerTextActive,
              ]}
            >
              Filter {activeFilterCount > 0 ? `(${activeFilterCount})` : ""}
            </Text>
          </TouchableOpacity>
        </View>

        {/* Quick Filter Tag Bar */}
        <View style={styles.quickTagsBar}>
          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.quickTagsScroll}
          >
            <TouchableOpacity
              activeOpacity={0.75}
              onPress={() =>
                setFilters((prev) => ({
                  ...prev,
                  availableOnly: !prev.availableOnly,
                }))
              }
              style={[
                styles.quickTag,
                filters.availableOnly && styles.quickTagActive,
              ]}
            >
              <CheckCircle2
                size={12}
                color={filters.availableOnly ? "#FFFFFF" : "#059669"}
                style={{ marginRight: 4 }}
              />
              <Text
                style={[
                  styles.quickTagText,
                  filters.availableOnly && styles.quickTagTextActive,
                ]}
              >
                Tersedia Saja
              </Text>
            </TouchableOpacity>

            <TouchableOpacity
              activeOpacity={0.75}
              onPress={() =>
                setFilters((prev) => ({
                  ...prev,
                  timeSlot: prev.timeSlot === "morning" ? "all" : "morning",
                }))
              }
              style={[
                styles.quickTag,
                filters.timeSlot === "morning" && styles.quickTagActive,
              ]}
            >
              <Text
                style={[
                  styles.quickTagText,
                  filters.timeSlot === "morning" && styles.quickTagTextActive,
                ]}
              >
                Pagi (06:00 - 12:00)
              </Text>
            </TouchableOpacity>

            <TouchableOpacity
              activeOpacity={0.75}
              onPress={() =>
                setFilters((prev) => ({
                  ...prev,
                  timeSlot: prev.timeSlot === "afternoon" ? "all" : "afternoon",
                }))
              }
              style={[
                styles.quickTag,
                filters.timeSlot === "afternoon" && styles.quickTagActive,
              ]}
            >
              <Text
                style={[
                  styles.quickTagText,
                  filters.timeSlot === "afternoon" && styles.quickTagTextActive,
                ]}
              >
                Siang (12:00 - 18:00)
              </Text>
            </TouchableOpacity>

            <TouchableOpacity
              activeOpacity={0.75}
              onPress={() =>
                setFilters((prev) => ({
                  ...prev,
                  sortBy: prev.sortBy === "cheapest" ? "earliest" : "cheapest",
                }))
              }
              style={[
                styles.quickTag,
                filters.sortBy === "cheapest" && styles.quickTagActive,
              ]}
            >
              <Text
                style={[
                  styles.quickTagText,
                  filters.sortBy === "cheapest" && styles.quickTagTextActive,
                ]}
              >
                Tarif Termurah
              </Text>
            </TouchableOpacity>
          </ScrollView>
        </View>

        <View style={styles.resultCountRow}>
          <Text style={styles.resultCountText}>
            Menampilkan{" "}
            <Text style={styles.resultCountBold}>
              {processedSchedules.length} Armada
            </Text>
          </Text>
          <Text style={styles.resultDateText}>
            {dateOptions.find((d) => d.dateStr === selectedDate)?.dayName},{" "}
            {dateOptions.find((d) => d.dateStr === selectedDate)?.dayNum}{" "}
            {dateOptions.find((d) => d.dateStr === selectedDate)?.monthName}
          </Text>
        </View>

        {loading && !refreshing ? (
          <ActivityIndicator
            size="large"
            color={COLORS.brandBlue}
            style={{ marginVertical: 40 }}
          />
        ) : processedSchedules.length > 0 ? (
          processedSchedules.map((item, idx) => {
            const busName = item.bus?.name || "Resi Bisma";
            const busType = item.bus?.bus_type || "Bus Reguler";
            const origin = item.route?.origin || originCity;
            const destination = item.route?.destination || destCity;
            const depTime = item.departure_time
              ? item.departure_time.substring(11, 16) ||
                item.departure_time.substring(0, 5)
              : "07:00";
            const arrTime = item.arrival_time
              ? item.arrival_time.substring(11, 16) ||
                item.arrival_time.substring(0, 5)
              : "15:00";
            const price = Number(item.price || 140000).toLocaleString("id-ID");

            const isDeparted = isScheduleDeparted(item);
            const stops = getStopsPreview(origin, destination);

            const nameLower = (busName || "").toLowerCase();
            let thumbSource = require("../../assets/images/resiBisma.webp");
            if (nameLower.includes("primadona"))
              thumbSource = require("../../assets/images/primadona.webp");
            if (nameLower.includes("bentas"))
              thumbSource = require("../../assets/images/bentas01.webp");
            if (nameLower.includes("kyloren"))
              thumbSource = require("../../assets/images/kylorenParwis.webp");

            return (
              <View
                key={item.id || idx}
                style={[
                  styles.ticketCard,
                  isDeparted && styles.ticketCardDeparted,
                ]}
              >
                {/* Header Bus Row */}
                <View style={styles.cardHeader}>
                  <View style={styles.busInfoLeft}>
                    <Image
                      source={thumbSource}
                      style={[
                        styles.busAvatar,
                        isDeparted && { opacity: 0.55 },
                      ]}
                      resizeMode="cover"
                    />
                    <View>
                      <Text
                        style={[
                          styles.busNameText,
                          isDeparted && { color: "#6B7280" },
                        ]}
                      >
                        {busName}
                      </Text>
                      <Text style={styles.busClassText}>
                        {busType} • 50 Kursi (2-2)
                      </Text>
                    </View>
                  </View>

                  {isDeparted ? (
                    <View style={styles.departedBadge}>
                      <Text style={styles.departedBadgeText}>BERANGKAT</Text>
                    </View>
                  ) : (
                    <View style={styles.ratingBadge}>
                      <Star
                        size={10}
                        color="#D97706"
                        fill="#D97706"
                        style={{ marginRight: 3 }}
                      />
                      <Text style={styles.ratingBadgeText}>4.9</Text>
                    </View>
                  )}
                </View>

                {/* Main Departure Timeline Flow */}
                <View style={styles.timelineContainer}>
                  {/* Left: Departure */}
                  <View style={styles.timeCol}>
                    <Text
                      style={[
                        styles.bigTimeText,
                        isDeparted && { color: "#6B7280" },
                      ]}
                    >
                      {depTime}
                    </Text>
                    <Text style={styles.terminalNameText} numberOfLines={1}>
                      {origin}
                    </Text>
                  </View>

                  {/* Middle: Duration & Route Line */}
                  <View style={styles.lineCol}>
                    <Text style={styles.durationText}>8j 00m</Text>
                    <View style={styles.dashedLineRow}>
                      <View style={styles.lineDot} />
                      <View style={styles.lineBar} />
                      <Bus
                        size={13}
                        color={isDeparted ? "#9CA3AF" : COLORS.brandBlue}
                        style={{ marginHorizontal: 4 }}
                      />
                      <View style={styles.lineBar} />
                      <View style={styles.lineDot} />
                    </View>
                    <Text style={styles.transitText}>Via Tol Cipali</Text>
                  </View>

                  {/* Right: Arrival */}
                  <View style={[styles.timeCol, { alignItems: "flex-end" }]}>
                    <Text
                      style={[
                        styles.bigTimeText,
                        isDeparted && { color: "#6B7280" },
                      ]}
                    >
                      {arrTime}
                    </Text>
                    <Text
                      style={[styles.terminalNameText, { textAlign: "right" }]}
                      numberOfLines={1}
                    >
                      {destination}
                    </Text>
                  </View>
                </View>

                {/* Intermediate Stops Strip */}
                <View style={styles.stopsBox}>
                  <Navigation
                    size={11}
                    color="#6B7280"
                    style={{ marginRight: 4 }}
                  />
                  <Text style={styles.stopsBoxText} numberOfLines={1}>
                    Lintas: {stops.join(" • ")}
                  </Text>
                </View>

                {/* Next Departure Banner if Departed Today */}
                {isDeparted ? (
                  <View style={styles.nextDepartureCard}>
                    <Calendar
                      size={13}
                      color="#4B5563"
                      style={{ marginRight: 6 }}
                    />
                    <Text style={styles.nextDepartureCardText}>
                      Trip Hari Ini Selesai •{" "}
                      <Text style={styles.nextDepartureCardBold}>
                        Trip Berikutnya: Besok ({tomorrowOption.dayNum}{" "}
                        {tomorrowOption.monthName}) {depTime} WIB
                      </Text>
                    </Text>
                  </View>
                ) : null}

                {/* Footer: Seat info & Action CTA */}
                <View style={styles.cardFooter}>
                  <View>
                    <Text style={styles.fareLabel}>Harga per orang</Text>
                    <Text
                      style={[
                        styles.fareValue,
                        isDeparted && { color: "#6B7280" },
                      ]}
                    >
                      Rp {price}
                    </Text>
                  </View>

                  {isDeparted ? (
                    <TouchableOpacity
                      activeOpacity={0.85}
                      onPress={() => {
                        setSelectedDate(tomorrowOption.dateStr);
                        Alert.alert(
                          "Pemesanan Jadwal Besok",
                          `Armada ${busName} hari ini telah diberangkatkan pukul ${depTime} WIB.\n\nTanggal telah dialihkan ke BESOK (${tomorrowOption.dayNum} ${tomorrowOption.monthName} 2026). Silakan pilih kursi keberangkatan besok.`,
                        );
                      }}
                      style={styles.tomorrowActionBtn}
                    >
                      <Text style={styles.tomorrowActionText}>Pesan Besok</Text>
                      <ArrowRight
                        size={13}
                        color="#FFFFFF"
                        style={{ marginLeft: 4 }}
                      />
                    </TouchableOpacity>
                  ) : (
                    <TouchableOpacity
                      activeOpacity={0.85}
                      onPress={() => {
                        navigation.navigate("ScheduleDetail", {
                          scheduleId: item.id,
                        });
                      }}
                      style={styles.bookActionBtn}
                    >
                      <Text style={styles.bookActionText}>Pilih Kursi</Text>
                      <ChevronRight
                        size={14}
                        color="#FFFFFF"
                        style={{ marginLeft: 3 }}
                      />
                    </TouchableOpacity>
                  )}
                </View>
              </View>
            );
          })
        ) : (
          <EmptyState
            icon={Bus}
            title="Tidak Ada Jadwal Ditemukan"
            description="Tidak ada armada yang sesuai dengan filter atau tanggal yang Anda pilih."
            actionLabel="Reset Semua Filter"
            onAction={() => {
              setSearchQuery("");
              setFilters({
                sortBy: "earliest",
                timeSlot: "all",
                availableOnly: false,
                destinationArea: "all",
              });
            }}
          />
        )}

        <View style={{ height: 110 }} />
      </ScrollView>

      {/* Filter Modal Bottom Sheet */}
      <ScheduleFilterModal
        visible={filterModalVisible}
        onClose={() => setFilterModalVisible(false)}
        filters={filters}
        onApply={(newFilters) => setFilters(newFilters)}
        matchedCount={processedSchedules.length}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  tripSummaryHeader: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 10,
  },
  routeSwapRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#F8FAFC",
    borderRadius: 14,
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 10,
  },
  routeCityBox: {
    flex: 1,
  },
  routeCityLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#9CA3AF",
    letterSpacing: 0.5,
  },
  routeCityName: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14.5,
    color: "#111827",
    marginTop: 1,
  },
  swapBtn: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
    marginHorizontal: 8,
  },
  dateRibbonScroll: {
    flexDirection: "row",
    gap: 8,
    paddingVertical: 2,
  },
  dateCard: {
    width: 68,
    height: 74,
    borderRadius: 14,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
    paddingVertical: 4,
  },
  dateCardActive: {
    backgroundColor: COLORS.brandBlue,
    borderColor: COLORS.brandBlue,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandBlue,
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.25,
        shadowRadius: 6,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  dateDayText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 10.5,
    color: "#6B7280",
  },
  dateDayTextActive: {
    color: "#FFFFFF",
  },
  dateNumText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#111827",
    marginVertical: 1,
  },
  dateNumTextActive: {
    color: "#FFFFFF",
  },
  dateMonthText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 9.5,
    color: "#6B7280",
  },
  dateMonthTextActive: {
    color: "rgba(255, 255, 255, 0.85)",
  },
  dateFareDot: {
    marginTop: 2,
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 4,
    paddingVertical: 1,
    borderRadius: 4,
  },
  dateFareDotActive: {
    backgroundColor: "rgba(255, 255, 255, 0.25)",
  },
  dateFareText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 8.5,
    color: COLORS.brandBlue,
  },
  dateFareTextActive: {
    color: "#FFFFFF",
  },
  toolbarSection: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 8,
    gap: 8,
  },
  searchBar: {
    flex: 1,
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    borderRadius: 12,
    paddingHorizontal: 10,
    height: 38,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  searchInput: {
    flex: 1,
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12.5,
    color: "#111827",
    paddingVertical: 0,
  },
  filterTriggerBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    paddingHorizontal: 12,
    height: 38,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  filterTriggerBtnActive: {
    backgroundColor: COLORS.brandBlue,
    borderColor: COLORS.brandBlue,
  },
  filterTriggerText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#374151",
  },
  filterTriggerTextActive: {
    color: "#FFFFFF",
  },
  quickTagsBar: {
    marginBottom: 10,
  },
  quickTagsScroll: {
    flexDirection: "row",
    gap: 8,
  },
  quickTag: {
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: 16,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  quickTagActive: {
    backgroundColor: "#0F172A",
    borderColor: "#0F172A",
  },
  quickTagText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11,
    color: "#4B5563",
  },
  quickTagTextActive: {
    color: "#FFFFFF",
    fontFamily: "PlusJakartaSans_700Bold",
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingTop: 12,
  },
  resultCountRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 10,
  },
  resultCountText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#6B7280",
  },
  resultCountBold: {
    fontFamily: "PlusJakartaSans_700Bold",
    color: "#111827",
  },
  resultDateText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 12,
    color: COLORS.brandBlue,
  },
  ticketCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 12,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.05,
        shadowRadius: 8,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  ticketCardDeparted: {
    backgroundColor: "#F8FAFC",
    borderColor: "#E2E8F0",
  },
  cardHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 12,
    paddingBottom: 10,
    borderBottomWidth: 1,
    borderBottomColor: "#F1F4F8",
  },
  busInfoLeft: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
  },
  busAvatar: {
    width: 38,
    height: 38,
    borderRadius: 10,
  },
  busNameText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14.5,
    color: "#111827",
  },
  busClassText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
    marginTop: 1,
  },
  ratingBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(217, 119, 6, 0.1)",
    paddingHorizontal: 6,
    paddingVertical: 2,
    borderRadius: 6,
  },
  ratingBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10.5,
    color: "#D97706",
  },
  departedBadge: {
    backgroundColor: "#E5E7EB",
    paddingHorizontal: 7,
    paddingVertical: 2,
    borderRadius: 6,
  },
  departedBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#6B7280",
  },
  timelineContainer: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    marginBottom: 10,
  },
  timeCol: {
    width: 90,
  },
  bigTimeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: "#111827",
  },
  terminalNameText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11.5,
    color: "#6B7280",
    marginTop: 2,
  },
  lineCol: {
    flex: 1,
    alignItems: "center",
    paddingHorizontal: 6,
  },
  durationText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 10,
    color: "#6B7280",
    marginBottom: 2,
  },
  dashedLineRow: {
    flexDirection: "row",
    alignItems: "center",
    width: "100%",
    justifyContent: "center",
  },
  lineDot: {
    width: 5,
    height: 5,
    borderRadius: 2.5,
    backgroundColor: "#CBD5E1",
  },
  lineBar: {
    flex: 1,
    height: 1.5,
    backgroundColor: "#CBD5E1",
  },
  transitText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 9.5,
    color: COLORS.brandBlue,
    marginTop: 2,
  },
  stopsBox: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F8FAFC",
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 8,
    marginBottom: 10,
  },
  stopsBoxText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
    flex: 1,
  },
  nextDepartureCard: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F1F5F9",
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 8,
    marginBottom: 10,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  nextDepartureCardText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#4B5563",
    flex: 1,
  },
  nextDepartureCardBold: {
    fontFamily: "PlusJakartaSans_700Bold",
    color: "#1E293B",
  },
  cardFooter: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingTop: 10,
    borderTopWidth: 1,
    borderTopColor: "#F1F4F8",
  },
  fareLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10,
    color: "#6B7280",
  },
  fareValue: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: COLORS.brandBlue,
  },
  bookActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 16,
    paddingVertical: 9,
    borderRadius: 12,
  },
  bookActionText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#FFFFFF",
  },
  tomorrowActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#0F172A",
    paddingHorizontal: 14,
    paddingVertical: 8.5,
    borderRadius: 12,
  },
  tomorrowActionText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#FFFFFF",
  },
});
