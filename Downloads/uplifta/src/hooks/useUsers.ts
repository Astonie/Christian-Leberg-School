"use client";

import { useQuery } from "@tanstack/react-query";
import { listUsers, listRoles } from "@/services/users";

export function useUsers() { return useQuery({ queryKey: ["users"], queryFn: listUsers }); }
export function useRoles() { return useQuery({ queryKey: ["roles"], queryFn: listRoles }); }


