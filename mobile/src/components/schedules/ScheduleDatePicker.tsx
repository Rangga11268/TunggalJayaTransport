import React from "react";
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
  Platform,
} from "react-native";
import { COLORS } from "../../theme/colors";

export interface DateOption {
  dayName: string;
  dayNum: number;
  monthName: string;
  dateStr: string;
  isToday: boolean;
  isTomorrow: boolean;
}

interface ScheduleDatePickerProps {
  dateOptions: DateOption[];
  selectedDate: string;
  onSelectDate: (dateStr: string) => void;
}

export const ScheduleDatePicker: React.FC<ScheduleDatePickerProps> = ({
  dateOptions,
  selectedDate,
  onSelectDate,
}) => {
  return (
    <View style={styles.calendarStripSection}>
      <ScrollView
        horizontal
        showsHorizontalScrollIndicator={false}
        contentContainerStyle={styles.calendarScroll}
      >
        {dateOptions.map((item) => {
          const isSelected = item.dateStr === selectedDate;
          return (
            <TouchableOpacity
              key={item.dateStr}
              activeOpacity={0.8}
              onPress={() => onSelectDate(item.dateStr)}
              style={[
                styles.dateCard,
                isSelected && styles.dateCardActive,
                item.isToday && !isSelected && styles.dateCardToday,
              ]}
            >
              {item.isToday ? (
                <View
                  style={[
                    styles.dayTagBadge,
                    isSelected && styles.dayTagBadgeActive,
                  ]}
                >
                  <Text
                    style={[
                      styles.dayTagText,
                      isSelected && styles.dayTagTextActive,
                    ]}
                  >
                    HARI INI
                  </Text>
                </View>
              ) : item.isTomorrow ? (
                <View
                  style={[
                    styles.dayTagBadge,
                    { backgroundColor: isSelected ? "#3B82F6" : "#E0F2FE" },
                  ]}
                >
                  <Text
                    style={[
                      styles.dayTagText,
                      { color: isSelected ? "#FFFFFF" : "#0284C7" },
                    ]}
                  >
                    BESOK
                  </Text>
                </View>
              ) : (
                <Text
                  style={[
                    styles.dayNameText,
                    isSelected && styles.dayNameTextActive,
                  ]}
                >
                  {item.dayName}
                </Text>
              )}

              <Text
                style={[
                  styles.dayNumText,
                  isSelected && styles.dayNumTextActive,
                ]}
              >
                {item.dayNum}
              </Text>
              <Text
                style={[
                  styles.monthText,
                  isSelected && styles.monthTextActive,
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
  );
};

const styles = StyleSheet.create({
  calendarStripSection: {
    backgroundColor: "#FFFFFF",
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: "#E5E7EB",
  },
  calendarScroll: {
    paddingHorizontal: 16,
    gap: 8,
  },
  dateCard: {
    width: 68,
    paddingVertical: 8,
    paddingHorizontal: 4,
    borderRadius: 14,
    backgroundColor: "#F9FAFB",
    borderWidth: 1.5,
    borderColor: "#E5E7EB",
    alignItems: "center",
    justifyContent: "center",
  },
  dateCardActive: {
    backgroundColor: COLORS.brandBlue,
    borderColor: COLORS.brandBlue,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandBlue,
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.3,
        shadowRadius: 6,
      },
      android: {
        elevation: 4,
      },
    }),
  },
  dateCardToday: {
    borderColor: "#BFDBFE",
    backgroundColor: "#EFF6FF",
  },
  dayTagBadge: {
    backgroundColor: "#DBEAFE",
    paddingHorizontal: 5,
    paddingVertical: 2,
    borderRadius: 4,
    marginBottom: 2,
  },
  dayTagBadgeActive: {
    backgroundColor: "rgba(255, 255, 255, 0.25)",
  },
  dayTagText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 7.5,
    color: COLORS.brandBlue,
    letterSpacing: 0.2,
  },
  dayTagTextActive: {
    color: "#FFFFFF",
  },
  dayNameText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 10,
    color: "#6B7280",
    marginBottom: 2,
  },
  dayNameTextActive: {
    color: "rgba(255, 255, 255, 0.8)",
  },
  dayNumText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: "#111827",
    lineHeight: 20,
  },
  dayNumTextActive: {
    color: "#FFFFFF",
  },
  monthText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10,
    color: "#9CA3AF",
    marginTop: 1,
  },
  monthTextActive: {
    color: "rgba(255, 255, 255, 0.8)",
  },
  dateFareDot: {
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 5,
    paddingVertical: 2,
    borderRadius: 6,
    marginTop: 4,
  },
  dateFareDotActive: {
    backgroundColor: "rgba(255, 255, 255, 0.2)",
  },
  dateFareText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9,
    color: COLORS.brandBlue,
  },
  dateFareTextActive: {
    color: "#FFFFFF",
  },
});
