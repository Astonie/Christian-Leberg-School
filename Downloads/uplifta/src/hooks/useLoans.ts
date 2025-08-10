"use client";

import { useQuery } from "@tanstack/react-query";
import { listLoans } from "@/services/loans";

export function useLoans() {
  return useQuery({ queryKey: ["loans"], queryFn: listLoans });
}


