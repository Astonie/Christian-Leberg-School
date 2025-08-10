import { Client } from "./types";

const MOCK_CLIENTS: Client[] = Array.from({ length: 42 }).map((_, i) => ({
  id: `${1000 + i}`,
  name: `Client ${i + 1}`,
  phone: `+256 700 00${i.toString().padStart(2, "0")}`,
  email: `client${i + 1}@uplifta.test`,
  createdAt: new Date(Date.now() - i * 86400000).toISOString(),
  loansCount: Math.floor(Math.random() * 5),
}));

export async function listClients(): Promise<Client[]> {
  await new Promise((r) => setTimeout(r, 500));
  return MOCK_CLIENTS;
}


