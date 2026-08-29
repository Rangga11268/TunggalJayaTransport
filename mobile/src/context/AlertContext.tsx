import React, {
  createContext,
  useContext,
  useState,
  useRef,
  useCallback,
} from "react";
import {
  View,
  Text,
  StyleSheet,
  Modal,
  TouchableOpacity,
  Animated,
  Dimensions,
  Platform,
} from "react-native";
import { COLORS } from "../theme/colors";
import {
  AlertSuccessSvg,
  AlertErrorSvg,
  AlertWarningSvg,
  AlertInfoSvg,
  AlertConfirmSvg,
} from "../components/ServiceIcons";

export type AlertType = "success" | "error" | "warning" | "info" | "confirm";

export interface AlertButton {
  text: string;
  style?: "default" | "cancel" | "destructive";
  onPress?: () => void | Promise<void>;
}

export interface ShowAlertOptions {
  title: string;
  message?: string;
  type?: AlertType;
  buttons?: AlertButton[];
  onDismiss?: () => void;
  confirmText?: string;
  cancelText?: string;
  onConfirm?: () => void | Promise<void>;
  onCancel?: () => void;
}

interface AlertContextType {
  showAlert: (options: ShowAlertOptions) => void;
  showSuccess: (
    title: string,
    message?: string,
    onConfirm?: () => void,
  ) => void;
  showError: (title: string, message?: string, onConfirm?: () => void) => void;
  showWarning: (
    title: string,
    message?: string,
    onConfirm?: () => void,
  ) => void;
  showInfo: (title: string, message?: string, onConfirm?: () => void) => void;
  showConfirm: (
    title: string,
    message: string,
    onConfirm: () => void | Promise<void>,
    onCancel?: () => void,
    destructive?: boolean,
  ) => void;
  hideAlert: () => void;
}

const AlertContext = createContext<AlertContextType | undefined>(undefined);

export const AlertProvider: React.FC<{ children: React.ReactNode }> = ({
  children,
}) => {
  const [visible, setVisible] = useState(false);
  const [config, setConfig] = useState<ShowAlertOptions>({
    title: "",
    message: "",
    type: "info",
  });

  const scaleAnim = useRef(new Animated.Value(0.85)).current;
  const opacityAnim = useRef(new Animated.Value(0)).current;

  const animateIn = () => {
    Animated.parallel([
      Animated.spring(scaleAnim, {
        toValue: 1,
        friction: 7,
        tension: 50,
        useNativeDriver: true,
      }),
      Animated.timing(opacityAnim, {
        toValue: 1,
        duration: 200,
        useNativeDriver: true,
      }),
    ]).start();
  };

  const animateOut = (callback?: () => void) => {
    Animated.parallel([
      Animated.timing(scaleAnim, {
        toValue: 0.85,
        duration: 150,
        useNativeDriver: true,
      }),
      Animated.timing(opacityAnim, {
        toValue: 0,
        duration: 150,
        useNativeDriver: true,
      }),
    ]).start(() => {
      setVisible(false);
      if (callback) callback();
    });
  };

  const showAlert = useCallback((options: ShowAlertOptions) => {
    setConfig(options);
    setVisible(true);
    scaleAnim.setValue(0.85);
    opacityAnim.setValue(0);
    animateIn();
  }, []);

  const hideAlert = useCallback(() => {
    animateOut(() => {
      if (config.onDismiss) config.onDismiss();
    });
  }, [config]);

  const showSuccess = useCallback(
    (title: string, message?: string, onConfirm?: () => void) => {
      showAlert({
        title,
        message,
        type: "success",
        confirmText: "Mengerti",
        onConfirm,
      });
    },
    [showAlert],
  );

  const showError = useCallback(
    (title: string, message?: string, onConfirm?: () => void) => {
      showAlert({
        title,
        message,
        type: "error",
        confirmText: "Tutup",
        onConfirm,
      });
    },
    [showAlert],
  );

  const showWarning = useCallback(
    (title: string, message?: string, onConfirm?: () => void) => {
      showAlert({
        title,
        message,
        type: "warning",
        confirmText: "Lanjutkan",
        onConfirm,
      });
    },
    [showAlert],
  );

  const showInfo = useCallback(
    (title: string, message?: string, onConfirm?: () => void) => {
      showAlert({
        title,
        message,
        type: "info",
        confirmText: "Oke",
        onConfirm,
      });
    },
    [showAlert],
  );

  const showConfirm = useCallback(
    (
      title: string,
      message: string,
      onConfirm: () => void | Promise<void>,
      onCancel?: () => void,
      destructive: boolean = false,
    ) => {
      showAlert({
        title,
        message,
        type: "confirm",
        buttons: [
          {
            text: "Batal",
            style: "cancel",
            onPress: onCancel,
          },
          {
            text: destructive ? "Ya, Lanjutkan" : "Konfirmasi",
            style: destructive ? "destructive" : "default",
            onPress: onConfirm,
          },
        ],
      });
    },
    [showAlert],
  );

  const getIcon = () => {
    switch (config.type) {
      case "success":
        return <AlertSuccessSvg size={58} />;
      case "error":
        return <AlertErrorSvg size={58} />;
      case "warning":
        return <AlertWarningSvg size={58} />;
      case "confirm":
        return <AlertConfirmSvg size={58} />;
      case "info":
      default:
        return <AlertInfoSvg size={58} />;
    }
  };

  const getThemeColor = () => {
    switch (config.type) {
      case "success":
        return {
          bg: "#ECFDF5",
          border: "#A7F3D0",
          button: "#10B981",
        };
      case "error":
        return {
          bg: "#FEF2F2",
          border: "#FECACA",
          button: "#EF4444",
        };
      case "warning":
        return {
          bg: "#FFFBEB",
          border: "#FDE68A",
          button: "#F59E0B",
        };
      case "confirm":
      case "info":
      default:
        return {
          bg: "#EFF6FF",
          border: "#BFDBFE",
          button: COLORS.brandBlue,
        };
    }
  };

  const theme = getThemeColor();

  return (
    <AlertContext.Provider
      value={{
        showAlert,
        showSuccess,
        showError,
        showWarning,
        showInfo,
        showConfirm,
        hideAlert,
      }}
    >
      {children}

      <Modal
        visible={visible}
        transparent={true}
        animationType="none"
        onRequestClose={hideAlert}
      >
        <View style={styles.overlay}>
          <TouchableOpacity
            style={styles.backdrop}
            activeOpacity={1}
            onPress={hideAlert}
          />

          <Animated.View
            style={[
              styles.card,
              {
                opacity: opacityAnim,
                transform: [{ scale: scaleAnim }],
              },
            ]}
          >
            {/* Header Icon Ring */}
            <View
              style={[
                styles.iconCircle,
                { backgroundColor: theme.bg, borderColor: theme.border },
              ]}
            >
              {getIcon()}
            </View>

            {/* Title & Message */}
            <Text style={styles.title}>{config.title}</Text>
            {config.message ? (
              <Text style={styles.message}>{config.message}</Text>
            ) : null}

            {/* Action Buttons */}
            <View style={styles.buttonContainer}>
              {config.buttons && config.buttons.length > 0 ? (
                <View style={styles.buttonRow}>
                  {config.buttons.map((btn, index) => {
                    const isCancel = btn.style === "cancel";
                    const isDestructive = btn.style === "destructive";

                    return (
                      <TouchableOpacity
                        key={index}
                        style={[
                          styles.btnBase,
                          isCancel
                            ? styles.btnCancel
                            : isDestructive
                              ? styles.btnDestructive
                              : styles.btnPrimary,
                          config.buttons!.length > 1 && { flex: 1 },
                        ]}
                        activeOpacity={0.85}
                        onPress={async () => {
                          animateOut(async () => {
                            if (btn.onPress) await btn.onPress();
                          });
                        }}
                      >
                        <Text
                          style={[
                            styles.btnText,
                            isCancel
                              ? styles.btnTextCancel
                              : styles.btnTextWhite,
                          ]}
                        >
                          {btn.text}
                        </Text>
                      </TouchableOpacity>
                    );
                  })}
                </View>
              ) : (
                <View style={styles.buttonRow}>
                  {config.onCancel && (
                    <TouchableOpacity
                      style={[styles.btnBase, styles.btnCancel, { flex: 1 }]}
                      activeOpacity={0.85}
                      onPress={() => {
                        animateOut(() => {
                          if (config.onCancel) config.onCancel();
                        });
                      }}
                    >
                      <Text style={[styles.btnText, styles.btnTextCancel]}>
                        {config.cancelText || "Batal"}
                      </Text>
                    </TouchableOpacity>
                  )}

                  <TouchableOpacity
                    style={[
                      styles.btnBase,
                      { backgroundColor: theme.button },
                      config.onCancel && { flex: 1.4 },
                    ]}
                    activeOpacity={0.85}
                    onPress={() => {
                      animateOut(() => {
                        if (config.onConfirm) config.onConfirm();
                      });
                    }}
                  >
                    <Text style={[styles.btnText, styles.btnTextWhite]}>
                      {config.confirmText || "Mengerti"}
                    </Text>
                  </TouchableOpacity>
                </View>
              )}
            </View>
          </Animated.View>
        </View>
      </Modal>
    </AlertContext.Provider>
  );
};

export const useCustomAlert = () => {
  const context = useContext(AlertContext);
  if (!context) {
    throw new Error("useCustomAlert must be used within an AlertProvider");
  }
  return context;
};

const { width } = Dimensions.get("window");

const styles = StyleSheet.create({
  overlay: {
    flex: 1,
    backgroundColor: "rgba(15, 23, 42, 0.65)",
    justifyContent: "center",
    alignItems: "center",
    padding: 20,
    zIndex: 9999,
  },
  backdrop: {
    position: "absolute",
    top: 0,
    bottom: 0,
    left: 0,
    right: 0,
  },
  card: {
    width: "100%",
    maxWidth: 380,
    backgroundColor: "#FFFFFF",
    borderRadius: 24,
    paddingHorizontal: 22,
    paddingTop: 24,
    paddingBottom: 20,
    alignItems: "center",
    borderWidth: 1,
    borderColor: "#F1F5F9",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 10 },
        shadowOpacity: 0.18,
        shadowRadius: 24,
      },
      android: {
        elevation: 12,
      },
      web: {
        boxShadow: "0 20px 40px -15px rgba(15, 23, 42, 0.25)",
      } as any,
    }),
  },
  iconCircle: {
    width: 68,
    height: 68,
    borderRadius: 34,
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 2,
    marginBottom: 16,
  },
  title: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: "#0F172A",
    textAlign: "center",
    marginBottom: 8,
    letterSpacing: -0.2,
  },
  message: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13.5,
    color: "#64748B",
    textAlign: "center",
    lineHeight: 20,
    marginBottom: 20,
    paddingHorizontal: 6,
  },
  buttonContainer: {
    width: "100%",
    marginTop: 4,
  },
  buttonRow: {
    flexDirection: "row",
    gap: 10,
    width: "100%",
  },
  btnBase: {
    paddingVertical: 12,
    paddingHorizontal: 16,
    borderRadius: 14,
    alignItems: "center",
    justifyContent: "center",
    width: "100%",
  },
  btnPrimary: {
    backgroundColor: COLORS.brandBlue,
  },
  btnCancel: {
    backgroundColor: "#F1F5F9",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  btnDestructive: {
    backgroundColor: "#DC2626",
  },
  btnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13.5,
  },
  btnTextWhite: {
    color: "#FFFFFF",
  },
  btnTextCancel: {
    color: "#64748B",
  },
});
