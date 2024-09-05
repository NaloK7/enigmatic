import { describe, it, expect, vi } from "vitest";
import { connect } from "@/views/user/login.vue"; // Ajustez le chemin d'importation
import { setToken } from "@/stores/tokenStore";
import { router } from "vue-router";
import { api } from "@/composables/api";

// Mock des fonctions et modules
vi.mock("../path/to/your/api", () => ({
  api: {
    getOne: vi.fn(),
  },
}));

vi.mock("../path/to/your/setToken", () => ({
  setToken: vi.fn(),
}));

vi.mock("../path/to/your/router", () => ({
  router: {
    push: vi.fn(),
  },
}));

describe("connect function", () => {
  it("should call setToken and router.push on successful login", async () => {
    // Arrange
    const email = { value: "test@example.com" };
    const password = { value: "password123" };
    const mockToken = "mockToken";

    // Simuler une réponse API réussie
    api.getOne.mockResolvedValue({
      status: 200,
      data: { token: mockToken },
    });

    // Act
    await connect(email, password);

    // Assert
    expect(api.getOne).toHaveBeenCalledWith("login", {
      email: email.value,
      password: password.value,
    });
    expect(setToken).toHaveBeenCalledWith(mockToken);
    expect(router.push).toHaveBeenCalledWith({ name: "home" });
  });

  it("should log an error if the API request fails", async () => {
    // Arrange
    const email = { value: "test@example.com" };
    const password = { value: "password123" };
    const mockError = { response: { status: 500 } };

    // Simuler une erreur API
    api.getOne.mockRejectedValue(mockError);

    // Spy sur console.log pour vérifier l'appel
    const consoleLogSpy = vi.spyOn(console, "log").mockImplementation(() => {});

    // Act
    await connect(email, password);

    // Assert
    expect(api.getOne).toHaveBeenCalledWith("login", {
      email: email.value,
      password: password.value,
    });
    expect(consoleLogSpy).toHaveBeenCalledWith(
      "An error as occurred:",
      mockError.response.status
    );

    // Nettoyage
    consoleLogSpy.mockRestore();
  });
});
