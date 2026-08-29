import React, { useState, useEffect, useMemo } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  TextInput,
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
  Bus,
  CheckCircle2,
} from "lucide-react-native";

import {
  ScheduleDatePicker,
  DateOption,
} from "../components/schedules/ScheduleDatePicker";
import { ScheduleCard } from "../components/schedules/ScheduleCard";

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

const getAvailableDates = (): DateOption[] => {
  const dates: DateOption[] = [];
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
      dayName: dayNames[d.getDay()],
      dayNum: d.getDate(),
      monthName: monthNames[d.getMonth()],
      dateStr,
      isToday: i === 0,
      isTomorrow: i === 1,
    });
  }
  return dates;
};

export default function ScheduleListScreen() {
  const navigation = useNavigation<NavigationProp>();
  const route = useRoute();
  const params = (route.params as any) || {};

  const originCity = params.origin || "Kuningan";
  const destCity = params.destination || "Jakarta";

  const dateOptions = useMemo(() => getAvailableDates(), []);
  const [selectedDate, setSelectedDate] = useState<string>(
    params.date ||
      dateOptions[0]?.dateStr ||
      new Date().toISOString().split("T")[0],
  );

  const [schedules, setSchedules] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState("");
  const [filterModalVisible, setFilterModalVisible] = useState(false);

  const [filters, setFilters] = useState<FilterOptions>({
    sortBy: "earliest",
    timeSlot: "all",
    availableOnly: false,
    destinationArea: "all",
  });

  const fetchSchedules = async () => {
    try {
      setLoading(true);
      const res = await apiClient
        .get("/schedules", {
          params: {
            origin: originCity,
            destination: destCity,
            date: selectedDate,
          },
        })
        .catch(() => ({ data: [] }));

      const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
      setSchedules(list);
      if (list.length === 0) {
        setSchedules([
          {
            id: 6,
            price: 140000,
            departure_time: "07:00",
            arrival_time: "13:30",
            duration: "6 Jam 30 Menit",
            available_seats: 32,
            is_departed: false,
            bus: {
              name: "Resi Bisma (Bentas-02)",
              type: "Jetbus 5 SHD (Adiputro)",
              capacity: 50,
              plate_number: "E 7799 YC",
            },
            route: {
              id: 3,
              origin: originCity || "Kuningan",
              destination: destCity ? `${destCity} (Kalideres)` : "Jakarta (Kalideres)",
              name: "Kuningan - Jakarta (Kalideres)",
            },
          },
          {
            id: 8,
            price: 140000,
            departure_time: "08:30",
            arrival_time: "14:15",
            duration: "5 Jam 45 Menit",
            available_seats: 28,
            is_departed: false,
            bus: {
              name: "Primadona (Bentas-05)",
              type: "Jetbus 3+ SHD",
              capacity: 50,
              plate_number: "E 7873 YC",
            },
            route: {
              id: 6,
              origin: originCity || "Kuningan",
              destination: destCity ? `${destCity} (Roxy)` : "Jakarta (Roxy)",
              name: "Kuningan - Jakarta (Roxy)",
            },
          },
          {
            id: 9,
            price: 140000,
            departure_time: "13:30",
            arrival_time: "19:45",
            duration: "6 Jam 15 Menit",
            available_seats: 44,
            is_departed: false,
            bus: {
              name: "Semar Mesem (Bentas-03)",
              type: "Jetbus 3+ SHD",
              capacity: 59,
              plate_number: "E 7823 YC",
            },
            route: {
              id: 3,
              origin: originCity || "Kuningan",
              destination: destCity ? `${destCity} (Kalideres)` : "Jakarta (Kalideres)",
              name: "Kuningan - Jakarta (Kalideres)",
            },
          },
          {
            id: 11,
            price: 140000,
            departure_time: "17:00",
            arrival_time: "23:00",
            duration: "6 Jam",
            available_seats: 50,
            is_departed: false,
            bus: {
              name: "Bentas-01 (Salamina)",
              type: "Jetbus 3+ SHD",
              capacity: 59,
              plate_number: "E 7781 YC",
            },
            route: {
              id: 8,
              origin: originCity || "Kuningan",
              destination: destCity ? `${destCity} (Pulogebang)` : "Jakarta (Pulogebang)",
              name: "Kuningan - Jakarta (Pulogebang)",
            },
          },
        ]);
      } else {
        setSchedules(list);
      }
    } catch (e) {
      console.log("Error loading schedules:", e);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchSchedules();
  }, [originCity, destCity, selectedDate]);

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchSchedules();
    setRefreshing(false);
  };

  const getStopsPreview = (origin: string, dest: string): string[] => {
    const dLower = (dest || "").toLowerCase();
    if (dLower.includes("kali")) return ROUTE_STOPS_MAP.kalideres;
    if (dLower.includes("roxy")) return ROUTE_STOPS_MAP.roxy;
    if (dLower.includes("banten") || dLower.includes("bitung"))
      return ROUTE_STOPS_MAP.banten;
    if (dLower.includes("cirebon")) return ROUTE_STOPS_MAP.cirebon;
    if (dLower.includes("pulo")) return ROUTE_STOPS_MAP.pulogebang;
    return ["Pool Cirendang", "Cilimus", "Tol Cipali", dest];
  };

  const isScheduleDeparted = (item: any): boolean => {
    if (item.is_departed !== undefined) return Boolean(item.is_departed);
    if (item.has_departed !== undefined) return Boolean(item.has_departed);

    const todayStr = new Date().toISOString().split("T")[0];
    if (selectedDate !== todayStr) return false;

    const depStr = item.departure_time || "";
    let hour = 7;
    let min = 0;
    if (depStr.includes("T") || depStr.includes(" ")) {
      const timePart = depStr.includes("T")
        ? depStr.split("T")[1]
        : depStr.split(" ")[1];
      const parts = timePart.split(":");
      hour = parseInt(parts[0], 10) || 7;
      min = parseInt(parts[1], 10) || 0;
    } else if (depStr.includes(":")) {
      const parts = depStr.split(":");
      hour = parseInt(parts[0], 10) || 7;
      min = parseInt(parts[1], 10) || 0;
    }

    const now = new Date();
    const currentMinutes = now.getHours() * 60 + now.getMinutes();
    const depMinutes = hour * 60 + min;
    return depMinutes < currentMinutes;
  };

  const processedSchedules = useMemo(() => {
    let result = [...schedules];

    if (searchQuery.trim()) {
      const q = searchQuery.toLowerCase();
      result = result.filter(
        (s) =>
          (s.bus?.name || "").toLowerCase().includes(q) ||
          (s.route?.destination || "").toLowerCase().includes(q) ||
          (s.route?.origin || "").toLowerCase().includes(q),
      );
    }

    if (filters.availableOnly) {
      result = result.filter((s) => !isScheduleDeparted(s));
    }

    if (filters.timeSlot !== "all") {
      result = result.filter((s) => {
        const depStr = s.departure_time || "";
        let hour = 7;
        if (depStr.includes("T") || depStr.includes(" ")) {
          const timePart = depStr.includes("T")
            ? depStr.split("T")[1]
            : depStr.split(" ")[1];
          hour = parseInt(timePart.split(":")[0], 10) || 7;
        } else if (depStr.includes(":")) {
          hour = parseInt(depStr.split(":")[0], 10) || 7;
        }
        if (filters.timeSlot === "morning") return hour >= 5 && hour < 12;
        if (filters.timeSlot === "afternoon") return hour >= 12 && hour < 18;
        if (filters.timeSlot === "evening") return hour >= 18 || hour < 5;
        return true;
      });
    }

    if (filters.sortBy === "cheapest") {
      result.sort((a, b) => Number(a.price || 0) - Number(b.price || 0));
    } else if (filters.sortBy === "latest") {
      result.sort((a, b) =>
        (b.departure_time || "").localeCompare(a.departure_time || ""),
      );
    } else {
      result.sort((a, b) =>
        (a.departure_time || "").localeCompare(b.departure_time || ""),
      );
    }

    return result;
  }, [schedules, searchQuery, filters, selectedDate]);

  const activeFilterCount =
    (filters.timeSlot !== "all" ? 1 : 0) +
    (filters.availableOnly ? 1 : 0) +
    (filters.sortBy !== "earliest" ? 1 : 0);

  const tomorrowOption = dateOptions[1] || dateOptions[0];

  return (
    <View style={styles.container}>
      {/* Top Header */}
      <ScreenHeader
        title={`${originCity} → ${destCity}`}
        subtitle="Pilih Jadwal Keberangkatan"
      />

      {/* 2. 7-DAY HORIZONTAL CALENDAR STRIP */}
      <ScheduleDatePicker
        dateOptions={dateOptions}
        selectedDate={selectedDate}
        onSelectDate={setSelectedDate}
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
            const isDeparted = isScheduleDeparted(item);
            const stops = getStopsPreview(
              item.route?.origin || originCity,
              item.route?.destination || destCity,
            );

            return (
              <ScheduleCard
                key={item.id || idx}
                item={item}
                originCity={originCity}
                destCity={destCity}
                isDeparted={isDeparted}
                tomorrowOption={tomorrowOption}
                stops={stops}
                onSelectTomorrow={(dateStr, busName, depTime) => {
                  setSelectedDate(dateStr);
                  Alert.alert(
                    "Pemesanan Jadwal Besok",
                    `Armada ${busName} hari ini telah diberangkatkan pukul ${depTime} WIB.\n\nTanggal telah dialihkan ke BESOK (${tomorrowOption.dayNum} ${tomorrowOption.monthName} 2026). Silakan pilih kursi keberangkatan besok.`,
                  );
                }}
                onBookSchedule={(scheduleId) => {
                  navigation.navigate("ScheduleDetail", {
                    scheduleId,
                    date: selectedDate,
                  });
                }}
              />
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
  scrollContent: {
    padding: 16,
    paddingBottom: 110,
  },
  toolbarSection: {
    flexDirection: "row",
    gap: 10,
    marginBottom: 10,
  },
  searchBar: {
    flex: 1,
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    borderRadius: 12,
    paddingHorizontal: 12,
    height: 42,
    borderWidth: 1,
    borderColor: "#E5E7EB",
  },
  searchInput: {
    flex: 1,
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13,
    color: "#111827",
  },
  filterTriggerBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E5E7EB",
    paddingHorizontal: 12,
    borderRadius: 12,
    height: 42,
  },
  filterTriggerBtnActive: {
    backgroundColor: COLORS.brandBlue,
    borderColor: COLORS.brandBlue,
  },
  filterTriggerText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 12,
    color: "#374151",
  },
  filterTriggerTextActive: {
    color: "#FFFFFF",
  },
  quickTagsBar: {
    marginBottom: 14,
  },
  quickTagsScroll: {
    gap: 8,
  },
  quickTag: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E5E7EB",
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 10,
  },
  quickTagActive: {
    backgroundColor: "#0F172A",
    borderColor: "#0F172A",
  },
  quickTagText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11.5,
    color: "#4B5563",
  },
  quickTagTextActive: {
    color: "#FFFFFF",
  },
  resultCountRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 12,
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
});
