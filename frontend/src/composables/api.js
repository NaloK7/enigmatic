export default ({ apiEnigm }) => ({
  async getAll(url, criteria) {
    const data = [];
    const xhr = await apiEnigm.post(url, {
      body: { ...criteria },
    });
    const response = await xhr.data;
    return response;
  },
});
