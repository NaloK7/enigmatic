// by convention, composable function names start with "use"
export function useEmailRule(email) {
  const emailRegex =
    /^[a-zA-Z]+[0-9a-zA-Z]*([.]*[0-9a-zA-Z]*){1,}(@)[a-z0-9A-Z.-]+[.]+([a-zA-Z]{2,})$/;
  return emailRegex.test(email);
}

export function usePasswordRule(value) {
  // use CNIL recommendation
  const passRegex =
    /^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[@&#{(\[\-|_\\)\]=}%?!\/]).{12,}$/;
  return passRegex.test(value);
}
