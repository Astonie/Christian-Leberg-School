"use client";

import { useQuery } from "@tanstack/react-query";
import { listClients } from "@/services/clients";

export function useClients() {
  return useQuery({ queryKey: ["clients"], queryFn: listClients });
}


