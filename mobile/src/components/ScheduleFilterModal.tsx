import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  Modal,
  TouchableOpacity,
  ScrollView,
  Platform,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import {
  X,
  Check,
  RotateCcw,
  Clock,
  ArrowUpDown,
  MapPin,
  CheckCircle2,
} from "lucide-react-native";
import { COLORS } from "../theme/colors";

export interface FilterOptions {
  sortBy: "earliest" | "latest" | "cheapest";
  timeSlot: "all" | "morning" | "afternoon" | "evening";
  availableOnly: boolean;
  destinationArea: "all" | "jakarta" | "banten" | "cirebon";
}

interface ScheduleFilterModalProps {
  visible: boolean;
  onClose: () => void;
  filters: FilterOptions;
  onApply: (newFilters: FilterOptions) => void;
  matchedCount: number;
}

export const ScheduleFilterModal: React.FC<ScheduleFilterModalProps> = ({
  visible,
  onClose,
  filters,
  onApply,
  matchedCount,
}) => {
  const [localFilters, setLocalFilters] = useState<FilterOptions>({
    ...filters,
  });

  const handleReset = () => {
    setLocalFilters({
      sortBy: "earliest",
      timeSlot: "all",
      availableOnly: false,
      destinationArea: "all",
    });
  };

  const handleApply = () => {
    onApply(localFilters);
    onClose();
  };

  const sortOptions = [
    {
      id: "earliest",
      label: "Keberangkatan Paling Awal",
      sub: "Pagi ke Malam",
    },
    { id: "latest", label: "Keberangkatan Paling Malam", sub: "Malam ke Pagi" },
    { id: "cheapest", label: "Harga Termurah", sub: "Tarif Terendah" },
  ];

  const timeSlotOptions = [
    { id: "all", label: "Semua Jam" },
    { id: "morning", label: "Pagi (06:00 - 12:00)" },
    { id: "afternoon", label: "Siang (12:00 - 18:00)" },
    { id: "evening", label: "Malam (18:00 - 24:00)" },
  ];

  const destinationOptions = [
    { id: "all", label: "Semua Rute & Tujuan" },
    { id: "jakarta", label: "Tujuan Jakarta (Kalideres, Roxy, Pulogebang)" },
    { id: "banten", label: "Tujuan Banten (Bitung, Rangkasbitung)" },
    { id: "cirebon", label: "Asal Cirebon (Ciledug, Pangkalan Asem)" },
  ];

  return (
    <Modal
      visible={visible}
      animationType="slide"
      transparent={true}
      onRequestClose={onClose}
    >
      <View style={styles.overlay}>
        <TouchableOpacity
          style={styles.backdrop}
          activeOpacity={1}
          onPress={onClose}
        />

        <View style={styles.sheetContainer}>
          {/* Header */}
          <View style={styles.header}>
            <View style={styles.headerLeft}>
              <TouchableOpacity onPress={onClose} style={styles.closeBtn}>
                <X size={20} color="#111827" />
              </TouchableOpacity>
              <Text style={styles.headerTitle}>Filter & Urutkan</Text>
            </View>

            <TouchableOpacity onPress={handleReset} style={styles.resetBtn}>
              <RotateCcw size={14} color="#6B7280" style={{ marginRight: 4 }} />
              <Text style={styles.resetBtnText}>Reset</Text>
            </TouchableOpacity>
          </View>

          <ScrollView
            showsVerticalScrollIndicator={false}
            contentContainerStyle={styles.scrollContent}
          >
            {/* 1. Urutkan Berdasarkan */}
            <View style={styles.section}>
              <View style={styles.sectionTitleRow}>
                <ArrowUpDown
                  size={16}
                  color={COLORS.brandBlue}
                  style={{ marginRight: 6 }}
                />
                <Text style={styles.sectionTitle}>Urutkan Jadwal</Text>
              </View>

              <View style={styles.optionsList}>
                {sortOptions.map((opt) => {
                  const isSelected = localFilters.sortBy === opt.id;
                  return (
                    <TouchableOpacity
                      key={opt.id}
                      activeOpacity={0.7}
                      onPress={() =>
                        setLocalFilters((prev) => ({
                          ...prev,
                          sortBy: opt.id as any,
                        }))
                      }
                      style={[
                        styles.radioRow,
                        isSelected && styles.radioRowActive,
                      ]}
                    >
                      <View style={styles.radioTextCol}>
                        <Text
                          style={[
                            styles.radioLabel,
                            isSelected && styles.radioLabelActive,
                          ]}
                        >
                          {opt.label}
                        </Text>
                        <Text style={styles.radioSub}>{opt.sub}</Text>
                      </View>

                      <View
                        style={[
                          styles.radioCircle,
                          isSelected && styles.radioCircleActive,
                        ]}
                      >
                        {isSelected && <View style={styles.radioDot} />}
                      </View>
                    </TouchableOpacity>
                  );
                })}
              </View>
            </View>

            {/* 2. Waktu Keberangkatan */}
            <View style={styles.section}>
              <View style={styles.sectionTitleRow}>
                <Clock
                  size={16}
                  color={COLORS.brandBlue}
                  style={{ marginRight: 6 }}
                />
                <Text style={styles.sectionTitle}>Waktu Keberangkatan</Text>
              </View>

              <View style={styles.gridPills}>
                {timeSlotOptions.map((opt) => {
                  const isSelected = localFilters.timeSlot === opt.id;
                  return (
                    <TouchableOpacity
                      key={opt.id}
                      activeOpacity={0.75}
                      onPress={() =>
                        setLocalFilters((prev) => ({
                          ...prev,
                          timeSlot: opt.id as any,
                        }))
                      }
                      style={[
                        styles.pillOption,
                        isSelected && styles.pillOptionActive,
                      ]}
                    >
                      <Text
                        style={[
                          styles.pillOptionText,
                          isSelected && styles.pillOptionTextActive,
                        ]}
                      >
                        {opt.label}
                      </Text>
                    </TouchableOpacity>
                  );
                })}
              </View>
            </View>

            {/* 3. Ketersediaan Tiket */}
            <View style={styles.section}>
              <View style={styles.sectionTitleRow}>
                <CheckCircle2
                  size={16}
                  color="#059669"
                  style={{ marginRight: 6 }}
                />
                <Text style={styles.sectionTitle}>Ketersediaan Kursi</Text>
              </View>

              <TouchableOpacity
                activeOpacity={0.8}
                onPress={() =>
                  setLocalFilters((prev) => ({
                    ...prev,
                    availableOnly: !prev.availableOnly,
                  }))
                }
                style={[
                  styles.toggleCard,
                  localFilters.availableOnly && styles.toggleCardActive,
                ]}
              >
                <View style={{ flex: 1 }}>
                  <Text style={styles.toggleCardTitle}>
                    Hanya Tampilkan yang Belum Berangkat
                  </Text>
                  <Text style={styles.toggleCardSub}>
                    Sembunyikan bus yang jamnya telah lewat hari ini
                  </Text>
                </View>
                <View
                  style={[
                    styles.checkboxBox,
                    localFilters.availableOnly && styles.checkboxBoxActive,
                  ]}
                >
                  {localFilters.availableOnly && (
                    <Check size={14} color="#FFFFFF" />
                  )}
                </View>
              </TouchableOpacity>
            </View>

            {/* 4. Wilayah Tujuan / Asal */}
            <View style={styles.section}>
              <View style={styles.sectionTitleRow}>
                <MapPin
                  size={16}
                  color={COLORS.brandBlue}
                  style={{ marginRight: 6 }}
                />
                <Text style={styles.sectionTitle}>Wilayah Tujuan</Text>
              </View>

              <View style={styles.optionsList}>
                {destinationOptions.map((opt) => {
                  const isSelected = localFilters.destinationArea === opt.id;
                  return (
                    <TouchableOpacity
                      key={opt.id}
                      activeOpacity={0.7}
                      onPress={() =>
                        setLocalFilters((prev) => ({
                          ...prev,
                          destinationArea: opt.id as any,
                        }))
                      }
                      style={[
                        styles.radioRow,
                        isSelected && styles.radioRowActive,
                      ]}
                    >
                      <Text
                        style={[
                          styles.radioLabel,
                          isSelected && styles.radioLabelActive,
                        ]}
                      >
                        {opt.label}
                      </Text>
                      <View
                        style={[
                          styles.radioCircle,
                          isSelected && styles.radioCircleActive,
                        ]}
                      >
                        {isSelected && <View style={styles.radioDot} />}
                      </View>
                    </TouchableOpacity>
                  );
                })}
              </View>
            </View>
          </ScrollView>

          {/* Sticky Bottom Apply Button */}
          <SafeAreaView edges={["bottom"]} style={styles.bottomBar}>
            <TouchableOpacity
              activeOpacity={0.85}
              onPress={handleApply}
              style={styles.applyBtn}
            >
              <Text style={styles.applyBtnText}>
                Terapkan Filter ({matchedCount} Jadwal)
              </Text>
            </TouchableOpacity>
          </SafeAreaView>
        </View>
      </View>
    </Modal>
  );
};

const styles = StyleSheet.create({
  overlay: {
    flex: 1,
    backgroundColor: "rgba(15, 23, 42, 0.65)",
    justifyContent: "flex-end",
  },
  backdrop: {
    flex: 1,
  },
  sheetContainer: {
    backgroundColor: "#FFFFFF",
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    maxHeight: "85%",
    ...Platform.select({
      ios: {
        shadowColor: "#000",
        shadowOffset: { width: 0, height: -4 },
        shadowOpacity: 0.15,
        shadowRadius: 12,
      },
      android: {
        elevation: 10,
      },
    }),
  },
  header: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 20,
    paddingVertical: 16,
    borderBottomWidth: 1,
    borderBottomColor: "#E2E8F0",
  },
  headerLeft: {
    flexDirection: "row",
    alignItems: "center",
  },
  closeBtn: {
    marginRight: 12,
    padding: 4,
  },
  headerTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: "#111827",
  },
  resetBtn: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 4,
    paddingHorizontal: 8,
  },
  resetBtnText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 13,
    color: "#6B7280",
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 16,
    paddingBottom: 24,
  },
  section: {
    marginBottom: 22,
  },
  sectionTitleRow: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 12,
  },
  sectionTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14.5,
    color: "#111827",
  },
  optionsList: {
    gap: 8,
  },
  radioRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingVertical: 12,
    paddingHorizontal: 14,
    borderRadius: 14,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  radioRowActive: {
    backgroundColor: "#EFF6FF",
    borderColor: COLORS.brandBlue,
  },
  radioTextCol: {
    flex: 1,
    marginRight: 10,
  },
  radioLabel: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 13,
    color: "#374151",
  },
  radioLabelActive: {
    color: COLORS.brandBlue,
    fontFamily: "PlusJakartaSans_700Bold",
  },
  radioSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11,
    color: "#6B7280",
    marginTop: 2,
  },
  radioCircle: {
    width: 20,
    height: 20,
    borderRadius: 10,
    borderWidth: 2,
    borderColor: "#D1D5DB",
    justifyContent: "center",
    alignItems: "center",
  },
  radioCircleActive: {
    borderColor: COLORS.brandBlue,
  },
  radioDot: {
    width: 10,
    height: 10,
    borderRadius: 5,
    backgroundColor: COLORS.brandBlue,
  },
  gridPills: {
    flexDirection: "row",
    flexWrap: "wrap",
    gap: 8,
  },
  pillOption: {
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderRadius: 20,
    backgroundColor: "#F1F5F9",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  pillOptionActive: {
    backgroundColor: COLORS.brandBlue,
    borderColor: COLORS.brandBlue,
  },
  pillOptionText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 12,
    color: "#4B5563",
  },
  pillOptionTextActive: {
    color: "#FFFFFF",
    fontFamily: "PlusJakartaSans_700Bold",
  },
  toggleCard: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    padding: 14,
    borderRadius: 14,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  toggleCardActive: {
    backgroundColor: "#F0FDF4",
    borderColor: "#86EFAC",
  },
  toggleCardTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#111827",
  },
  toggleCardSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11,
    color: "#6B7280",
    marginTop: 2,
  },
  checkboxBox: {
    width: 22,
    height: 22,
    borderRadius: 6,
    borderWidth: 2,
    borderColor: "#D1D5DB",
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
  },
  checkboxBoxActive: {
    backgroundColor: "#059669",
    borderColor: "#059669",
  },
  bottomBar: {
    paddingHorizontal: 20,
    paddingTop: 12,
    paddingBottom: 16,
    borderTopWidth: 1,
    borderTopColor: "#E2E8F0",
    backgroundColor: "#FFFFFF",
  },
  applyBtn: {
    backgroundColor: COLORS.brandBlue,
    paddingVertical: 13,
    borderRadius: 14,
    alignItems: "center",
    justifyContent: "center",
  },
  applyBtnText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#FFFFFF",
  },
});
