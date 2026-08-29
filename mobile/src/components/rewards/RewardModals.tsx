import React from "react";
import {
  View,
  Text,
  Modal,
  TouchableOpacity,
  ScrollView,
  StyleSheet,
} from "react-native";
import { Sparkles, X } from "lucide-react-native";
import { RewardItem } from "../../context/RewardContext";
import { RewardsLoyaltyIcon } from "../ServiceIcons";
import { COLORS } from "../../theme/colors";

interface RewardModalsProps {
  redeemingItem: RewardItem | null;
  isHistoryModalOpen: boolean;
  transactions: any[];
  onCloseRedeem: () => void;
  onConfirmRedeem: () => void;
  onCloseHistory: () => void;
}

export const RewardModals: React.FC<RewardModalsProps> = ({
  redeemingItem,
  isHistoryModalOpen,
  transactions,
  onCloseRedeem,
  onConfirmRedeem,
  onCloseHistory,
}) => {
  return (
    <>
      {/* CONFIRMATION REDEEM MODAL */}
      <Modal
        visible={!!redeemingItem}
        transparent={true}
        animationType="fade"
        onRequestClose={onCloseRedeem}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalCard}>
            <View style={styles.modalIconSvgBox}>
              <RewardsLoyaltyIcon size={64} />
            </View>
            <Text style={styles.modalTitle}>Konfirmasi Tukar Poin</Text>
            <Text style={styles.modalSub}>
              Apakah Anda ingin menukarkan{" "}
              <Text style={{ fontFamily: "PlusJakartaSans_800ExtraBold" }}>
                {redeemingItem?.pointsRequired} Poin
              </Text>{" "}
              untuk hadiah{" "}
              <Text style={{ fontFamily: "PlusJakartaSans_800ExtraBold" }}>
                {redeemingItem?.title}
              </Text>
              ?
            </Text>

            <View style={styles.modalActionRow}>
              <TouchableOpacity
                style={styles.modalCancelBtn}
                onPress={onCloseRedeem}
              >
                <Text style={styles.modalCancelText}>Batal</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={styles.modalConfirmBtn}
                onPress={onConfirmRedeem}
              >
                <Text style={styles.modalConfirmText}>Ya, Tukar Sekarang</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>

      {/* HISTORY MODAL */}
      <Modal
        visible={isHistoryModalOpen}
        transparent={true}
        animationType="slide"
        onRequestClose={onCloseHistory}
      >
        <View style={styles.modalOverlay}>
          <View style={[styles.modalCard, { maxHeight: "80%", width: "100%" }]}>
            <View style={styles.historyModalHeader}>
              <Text style={styles.modalTitle}>Riwayat Poin Loyalitas</Text>
              <TouchableOpacity onPress={onCloseHistory}>
                <X size={20} color="#64748B" />
              </TouchableOpacity>
            </View>

            <ScrollView showsVerticalScrollIndicator={false}>
              {transactions.map((tx) => (
                <View key={tx.id} style={styles.txRow}>
                  <View style={styles.txLeft}>
                    <View
                      style={[
                        styles.txDot,
                        tx.type === "earn"
                          ? { backgroundColor: "#DCFCE7" }
                          : { backgroundColor: "#FEE2E2" },
                      ]}
                    >
                      <Sparkles
                        size={14}
                        color={tx.type === "earn" ? "#16A34A" : "#DC2626"}
                      />
                    </View>
                    <View>
                      <Text style={styles.txTitle}>{tx.title}</Text>
                      <Text style={styles.txDate}>{tx.date}</Text>
                    </View>
                  </View>
                  <Text
                    style={[
                      styles.txPoints,
                      tx.type === "earn"
                        ? { color: "#16A34A" }
                        : { color: "#DC2626" },
                    ]}
                  >
                    {tx.type === "earn" ? "+" : "-"}
                    {tx.points.toLocaleString("id-ID")} Poin
                  </Text>
                </View>
              ))}
            </ScrollView>
          </View>
        </View>
      </Modal>
    </>
  );
};

const styles = StyleSheet.create({
  modalOverlay: {
    flex: 1,
    backgroundColor: "rgba(0, 0, 0, 0.5)",
    justifyContent: "center",
    alignItems: "center",
    padding: 20,
  },
  modalCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
    padding: 20,
    width: "100%",
    maxWidth: 380,
    alignItems: "center",
  },
  modalIconSvgBox: {
    marginBottom: 12,
  },
  modalTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#111827",
    textAlign: "center",
  },
  modalSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#6B7280",
    textAlign: "center",
    marginTop: 6,
    lineHeight: 18,
    marginBottom: 20,
  },
  modalActionRow: {
    flexDirection: "row",
    gap: 10,
    width: "100%",
  },
  modalCancelBtn: {
    flex: 1,
    paddingVertical: 11,
    borderRadius: 10,
    backgroundColor: "#F3F4F6",
    alignItems: "center",
  },
  modalCancelText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#4B5563",
  },
  modalConfirmBtn: {
    flex: 1.4,
    paddingVertical: 11,
    borderRadius: 10,
    backgroundColor: COLORS.brandBlue,
    alignItems: "center",
  },
  modalConfirmText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  historyModalHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    width: "100%",
    marginBottom: 16,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: "#E5E7EB",
  },
  txRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: "#F3F4F6",
    width: "100%",
  },
  txLeft: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
  },
  txDot: {
    width: 32,
    height: 32,
    borderRadius: 16,
    justifyContent: "center",
    alignItems: "center",
  },
  txTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#111827",
  },
  txDate: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#9CA3AF",
    marginTop: 1,
  },
  txPoints: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13,
  },
});
