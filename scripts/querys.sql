SELECT u.name, u.email, pp.logradouro, a.complemento, a.numero, a.bairro, a.cep, a.uf, au.tipo_endereco
FROM addres_user au
JOIN users u ON (u.id = au.users_id)
JOIN address a ON (a.id = au.address_id)
JOIN public_place pp ON (pp.id = a.public_place_id)