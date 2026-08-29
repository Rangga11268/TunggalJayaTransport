import React from "react";
import { View, Text, TextInput, StyleSheet } from "react-native";
import { User, Phone, Mail } from "lucide-react-native";
import { COLORS } from "../../theme/colors";

interface CheckoutPassengerFormProps {
  passengerName: string;
  passengerPhone: string;
  passengerEmail: string;
  focusedField: string | null;
  onSetName: (v: string) => void;
  onSetPhone: (v: string) => void;
  onSetEmail: (v: string) => void;
  onSetFocusedField: (v: string | null) => void;
}

export const CheckoutPassengerForm: React.FC<CheckoutPassengerFormProps> = ({
  passengerName,
  passengerPhone,
  passengerEmail,
  focusedField,
  onSetName,
  onSetPhone,
  onSetEmail,
  onSetFocusedField,
}) => {
  return (
    <View style={styles.card}>
      <Text style={styles.cardSectionTitle}>Data Penumpang</Text>

      {/* Name Field */}
      <View style={styles.inputGroup}>
        <Text style={styles.inputLabel}>NAMA LENGKAP (SESUAI KTP)</Text>
        <View
          style={[
            styles.inputContainer,
            focusedField === "name" && styles.inputContainerFocused,
          ]}
        >
          <User
            size={18}
            color={focusedField === "name" ? COLORS.brandBlue : "#6B7280"}
          />
          <TextInput
            style={styles.textInput}
            value={passengerName}
            onChangeText={onSetName}
            placeholder="Nama lengkap penumpang"
            placeholderTextColor="#9CA3AF"
            onFocus={() => onSetFocusedField("name")}
            onBlur={() => onSetFocusedField(null)}
          />
        </View>
      </View>

      {/* Phone Field */}
      <View style={styles.inputGroup}>
        <Text style={styles.inputLabel}>NOMOR WHATSAPP (AKTIF)</Text>
        <View
          style={[
            styles.inputContainer,
            focusedField === "phone" && styles.inputContainerFocused,
          ]}
        >
          <Phone
            size={18}
            color={focusedField === "phone" ? COLORS.brandBlue : "#6B7280"}
          />
          <TextInput
            style={styles.textInput}
            value={passengerPhone}
            onChangeText={onSetPhone}
            placeholder="08xxxxxxxxxx"
            placeholderTextColor="#9CA3AF"
            keyboardType="phone-pad"
            onFocus={() => onSetFocusedField("phone")}
            onBlur={() => onSetFocusedField(null)}
          />
        </View>
      </View>

      {/* Email Field */}
      <View style={styles.inputGroup}>
        <Text style={styles.inputLabel}>EMAIL KONFIRMASI</Text>
        <View
          style={[
            styles.inputContainer,
            focusedField === "email" && styles.inputContainerFocused,
          ]}
        >
          <Mail
            size={18}
            color={focusedField === "email" ? COLORS.brandBlue : "#6B7280"}
          />
          <TextInput
            style={styles.textInput}
            value={passengerEmail}
            onChangeText={onSetEmail}
            placeholder="email@domain.com"
            placeholderTextColor="#9CA3AF"
            keyboardType="email-address"
            autoCapitalize="none"
            onFocus={() => onSetFocusedField("email")}
            onBlur={() => onSetFocusedField(null)}
          />
        </View>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  card: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E5E7EB",
    marginBottom: 16,
  },
  cardSectionTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14.5,
    color: "#111827",
    marginBottom: 14,
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
});
