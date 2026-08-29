import React from "react";
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
} from "react-native";
import { MapPin, Calendar, Clock, Minus, Plus } from "lucide-react-native";
import { COLORS } from "../../theme/colors";
import { formatIndonesianDate } from "../../utils/format";

interface CharterBookingFormProps {
  pickup: string;
  destination: string;
  startDate: string;
  departureTime: string;
  daysCount: number;
  busCount: number;
  notes: string;
  focusedField: string | null;
  onSetPickup: (v: string) => void;
  onSetDestination: (v: string) => void;
  onSetStartDate: (v: string) => void;
  onSetDepartureTime: (v: string) => void;
  onSetDaysCount: (v: number) => void;
  onSetBusCount: (v: number) => void;
  onSetNotes: (v: string) => void;
  onSetFocusedField: (v: string | null) => void;
}

export const CharterBookingForm: React.FC<CharterBookingFormProps> = ({
  pickup,
  destination,
  startDate,
  departureTime,
  daysCount,
  busCount,
  notes,
  focusedField,
  onSetPickup,
  onSetDestination,
  onSetStartDate,
  onSetDepartureTime,
  onSetDaysCount,
  onSetBusCount,
  onSetNotes,
  onSetFocusedField,
}) => {
  return (
    <View style={styles.formCard}>
      <Text style={styles.formCardTitle}>Rencana Perjalanan Wisata</Text>

      {/* Pickup Location */}
      <View style={styles.inputGroup}>
        <Text style={styles.inputLabel}>LOKASI PENJEMPUTAN</Text>
        <View
          style={[
            styles.inputContainer,
            focusedField === "pickup" && styles.inputContainerFocused,
          ]}
        >
          <MapPin
            size={18}
            color={focusedField === "pickup" ? COLORS.brandBlue : "#6B7280"}
          />
          <TextInput
            style={styles.textInput}
            value={pickup}
            onChangeText={onSetPickup}
            placeholder="Contoh: Pool Cirendang Kuningan / Cirebon / Jakarta"
            placeholderTextColor="#9CA3AF"
            onFocus={() => onSetFocusedField("pickup")}
            onBlur={() => onSetFocusedField(null)}
          />
        </View>
      </View>

      {/* Destination */}
      <View style={styles.inputGroup}>
        <Text style={styles.inputLabel}>KOTA TUJUAN WISATA</Text>
        <View
          style={[
            styles.inputContainer,
            focusedField === "destination" && styles.inputContainerFocused,
          ]}
        >
          <MapPin
            size={18}
            color={
              focusedField === "destination" ? COLORS.brandBlue : "#6B7280"
            }
          />
          <TextInput
            style={styles.textInput}
            value={destination}
            onChangeText={onSetDestination}
            placeholder="Contoh: Yogyakarta / Bandung / Bali / Malang / Pangandaran"
            placeholderTextColor="#9CA3AF"
            onFocus={() => onSetFocusedField("destination")}
            onBlur={() => onSetFocusedField(null)}
          />
        </View>
      </View>

      {/* Start Date */}
      <View style={styles.inputGroup}>
        <View
          style={{
            flexDirection: "row",
            justifyContent: "space-between",
            alignItems: "center",
            marginBottom: 8,
          }}
        >
          <Text style={styles.inputLabel}>TANGGAL KEBERANGKATAN</Text>
          <Text
            style={{
              fontFamily: "PlusJakartaSans_700Bold",
              fontSize: 11,
              color: COLORS.brandBlue,
            }}
          >
            {formatIndonesianDate(startDate, false)}
          </Text>
        </View>
        <View
          style={[
            styles.inputContainer,
            focusedField === "startDate" && styles.inputContainerFocused,
          ]}
        >
          <Calendar
            size={18}
            color={focusedField === "startDate" ? COLORS.brandBlue : "#6B7280"}
          />
          <TextInput
            style={styles.textInput}
            value={startDate}
            onChangeText={onSetStartDate}
            placeholder="YYYY-MM-DD (Contoh: 2026-09-15)"
            placeholderTextColor="#9CA3AF"
            onFocus={() => onSetFocusedField("startDate")}
            onBlur={() => onSetFocusedField(null)}
          />
        </View>
      </View>

      {/* Departure Time */}
      <View style={styles.inputGroup}>
        <Text style={styles.inputLabel}>JAM KEBERANGKATAN PENJEMPUTAN</Text>
        <View
          style={[
            styles.inputContainer,
            focusedField === "time" && styles.inputContainerFocused,
          ]}
        >
          <Clock
            size={18}
            color={focusedField === "time" ? COLORS.brandBlue : "#6B7280"}
          />
          <TextInput
            style={styles.textInput}
            value={departureTime}
            onChangeText={onSetDepartureTime}
            placeholder="Contoh: 06:00 WIB"
            placeholderTextColor="#9CA3AF"
            onFocus={() => onSetFocusedField("time")}
            onBlur={() => onSetFocusedField(null)}
          />
        </View>
      </View>

      {/* Steppers: Duration & Bus Count */}
      <View style={styles.countersRow}>
        {/* Days Count */}
        <View style={styles.counterBox}>
          <Text style={styles.counterLabel}>DURASI (HARI)</Text>
          <View style={styles.stepperContainer}>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => onSetDaysCount(Math.max(1, daysCount - 1))}
              style={styles.stepBtn}
            >
              <Minus size={16} color="#111827" />
            </TouchableOpacity>
            <Text style={styles.stepValueText}>{daysCount} Hari</Text>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => onSetDaysCount(daysCount + 1)}
              style={styles.stepBtn}
            >
              <Plus size={16} color="#111827" />
            </TouchableOpacity>
          </View>
        </View>

        {/* Bus Count */}
        <View style={styles.counterBox}>
          <Text style={styles.counterLabel}>JUMLAH UNIT</Text>
          <View style={styles.stepperContainer}>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => onSetBusCount(Math.max(1, busCount - 1))}
              style={styles.stepBtn}
            >
              <Minus size={16} color="#111827" />
            </TouchableOpacity>
            <Text style={styles.stepValueText}>{busCount} Unit</Text>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => onSetBusCount(busCount + 1)}
              style={styles.stepBtn}
            >
              <Plus size={16} color="#111827" />
            </TouchableOpacity>
          </View>
        </View>
      </View>

      {/* Notes */}
      <View style={styles.inputGroup}>
        <Text style={styles.inputLabel}>CATATAN KHUSUS / TUJUAN TAMBAHAN</Text>
        <View
          style={[
            styles.textAreaContainer,
            focusedField === "notes" && styles.inputContainerFocused,
          ]}
        >
          <TextInput
            style={styles.textAreaInput}
            value={notes}
            onChangeText={onSetNotes}
            placeholder="Rencana rute singgah, daftar destinasi wisata, atau permintaan khusus..."
            placeholderTextColor="#9CA3AF"
            multiline
            numberOfLines={3}
            onFocus={() => onSetFocusedField("notes")}
            onBlur={() => onSetFocusedField(null)}
          />
        </View>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  formCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E5E7EB",
    marginBottom: 16,
  },
  formCardTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 15,
    color: "#111827",
    marginBottom: 16,
  },
  inputGroup: {
    marginBottom: 14,
  },
  inputLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10.5,
    color: "#6B7280",
    letterSpacing: 0.3,
    marginBottom: 6,
  },
  inputContainer: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F9FAFB",
    borderWidth: 1,
    borderColor: "#E5E7EB",
    borderRadius: 12,
    paddingHorizontal: 12,
    height: 46,
    gap: 8,
  },
  inputContainerFocused: {
    borderColor: COLORS.brandBlue,
    backgroundColor: "#FFFFFF",
  },
  textInput: {
    flex: 1,
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12.5,
    color: "#111827",
  },
  countersRow: {
    flexDirection: "row",
    gap: 12,
    marginBottom: 14,
  },
  counterBox: {
    flex: 1,
  },
  counterLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10.5,
    color: "#6B7280",
    letterSpacing: 0.3,
    marginBottom: 6,
  },
  stepperContainer: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#F9FAFB",
    borderWidth: 1,
    borderColor: "#E5E7EB",
    borderRadius: 12,
    paddingHorizontal: 6,
    height: 46,
  },
  stepBtn: {
    width: 32,
    height: 32,
    borderRadius: 8,
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E5E7EB",
    justifyContent: "center",
    alignItems: "center",
  },
  stepValueText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13,
    color: "#111827",
  },
  textAreaContainer: {
    backgroundColor: "#F9FAFB",
    borderWidth: 1,
    borderColor: "#E5E7EB",
    borderRadius: 12,
    padding: 10,
    height: 76,
  },
  textAreaInput: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#111827",
    textAlignVertical: "top",
    flex: 1,
  },
});
